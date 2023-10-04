<?php

namespace App\Http\Controllers;

use App\Exports\VotesExport;
use App\Models\Candidate;
use App\Models\Form;
use App\Models\Vote;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormController extends Controller
{
    private $cantidad_de_mesas = [
        'Provincial'     => 1800,
        'Adolfo_Alsina'  => 180,
        'Conesa'         => 18,
        'San_Antonio'    => 88,
        'Valcheta'       => 17,
        '9_de_Julio'     => 11,
        '25_de_Mayo'     => 42,
        'Ñorquinco'      => 8,
        'Pilcaniyeu'     => 30,
        'Bariloche'      => 385,
        'Pichi_Mahuida'  => 38,
        'Avellaneda'     => 96,
        'General_Roca'   => 86,
        'El_Cuy'         => 12,
    ];

    public function getCandidates() {
        $list = Candidate::select(['id', 'eleccion',  'lista', 'partido_sigla', 'nombre'])->orderBy('id', 'ASC')->get();
        $list->map(function($candidate) {
            if ($candidate->lista == 0) {
                $candidate->titulo = "$candidate->nombre";
            }else{
                $candidate->titulo = "Lista: $candidate->lista $candidate->partido_sigla/$candidate->nombre";
            }
        });
        return $list;
    }

    public function saveCandidates(Request $r) {
        $check_presidente = Form::where('mesa', $r->mesa_presidente)->where('eleccion', 'presidente')->count();
        $check_diputado = Form::where('mesa', $r->mesa_diputado)->where('eleccion', 'diputado')->count();
        if ($check_presidente > 0 || $check_diputado > 0) {
            return response()->json([
                'error' => 'Este formulario ya fue cargado!',
                'data' => [],
                'status' => 406
            ]);
        }

        $candidatos = Candidate::all();
        $error = true;
        $total_cargados_presidente = null;
        $total_cargados_diputado = null;
        $candidatos->map(function($candidato) use ($r, &$error, &$total_cargados_presidente) {
            if ($r->input('candidato_presidente_'.$candidato->id)) {
                $error = false;
                $total_cargados_presidente += $r->input('candidato_presidente_'.$candidato->id);
            }
        });

        $candidatos->map(function($candidato) use ($r, &$error, &$total_cargados_diputado) {
            if ($r->input('candidato_diputado_'.$candidato->id)) {
                $error = false;
                $total_cargados_diputado += $r->input('candidato_diputado_'.$candidato->id);
            }
        });

        if ($error) {
            return response()->json([
                'error' => 'No tiene votos cargados!',
                'data' => [],
                'status' => 406
            ]);
        }

        if ($r->total_presidente < $total_cargados_presidente || $r->total_diputado < $total_cargados_diputado) {
            return response()->json([
                'error' => 'El total de votos es mayor al total del padron!',
                'data' => [],
                'status' => 406
            ]);
        }

        $form_presidente = Form::create([
            'mesa' => $r->mesa_presidente,
            'eleccion' => 'presidente',
            'total_votantes' => $r->total_presidente,
        ]);
        $form_presidente->save();
        $candidatos->map(function($candidato) use ($r, $form_presidente) {
            Vote::insert([
                'cantidad' => $r->input('candidato_presidente_'.$candidato->id) ? $r->input('candidato_presidente_'.$candidato->id) : 0,
                'formulario_id' => $form_presidente->id,
                'candidato_id' => $candidato->id,
            ]);
        });
        $form_diputado = Form::create([
            'mesa' => $r->mesa_diputado,
            'eleccion' => 'diputado',
            'total_votantes' => $r->total_diputado,
        ]);
        $form_diputado->save();
        $candidatos->map(function($candidato) use ($r, $form_diputado) {
            Vote::insert([
                'cantidad' => $r->input('candidato_diputado_'.$candidato->id) ? $r->input('candidato_diputado_'.$candidato->id) : 0,
                'formulario_id' => $form_diputado->id,
                'candidato_id' => $candidato->id,
            ]);
        });
        return response()->json([
            'error' => '',
            'data' => [],
            'status' => 201
        ]);
    }

    public function getVotes(Request $r) {
        switch ($r->filter) {
            case 'Provincial':
                return $this->getByForm('Provincial', 0, 1800);
            break;
            case 'Adolfo_Alsina':
                return $this->getByForm('Adolfo_Alsina', 1, 180);
            break;
            case 'Conesa':
                return $this->getByForm('Conesa', 182, 200);
            break;
            case 'San_Antonio':
                return $this->getByForm('San_Antonio', 201, 289);
            break;
            case 'Valcheta':
                return $this->getByForm('Valcheta', 290, 307);
            break;
            case '9_de_Julio':
                return $this->getByForm('9_de_Julio', 308, 319);
            break;
            case '25_de_Mayo':
                return $this->getByForm('25_de_Mayo', 320, 362);
            break;
            case 'Ñorquinco':
                return $this->getByForm('Ñorquinco', 363, 371);
            break;
            case 'Pilcaniyeu':
                return $this->getByForm('Pilcaniyeu', 372, 402);
            break;
            case 'Bariloche':
                return $this->getByForm('Bariloche', 403, 788);
            break;
            case 'Pichi_Mahuida':
                return $this->getByForm('Pichi_Mahuida', 789, 827);
            break;
            case 'Avellaneda':
                return $this->getByForm('Avellaneda', 828, 924);
            break;
            case 'General_Roca':
                return $this->getByForm('General_Roca', 925, 1787);
            break;
            case 'El_Cuy':
                return $this->getByForm('El_Cuy', 1788, 1800);
            break;
        }
    }

    private function getByForm($zona, $desde, $hasta, $desde2 = null, $hasta2 = null) {
        $candidatos = Candidate::select('id', 'color', 'nombre')->where('id', '<=', 9)->get();
        $forms = [];
        if ($desde2) {
            $forms_a=Form::where('mesa', '>=', $desde)->where('mesa', '<=', $hasta);
            $forms=Form::where('mesa', '>=', $desde2)->where('mesa', '<=', $hasta2)->union($forms_a)->get();
        }else{
            $forms=Form::where('mesa', '>=', $desde)->where('mesa', '<=', $hasta)->get();
        }
        $res = [];
        $votos_totales=0;
        $forms->map(function($form) use (&$votos_totales) {
            $votos_del_form = Vote::where('formulario_id', $form->id)->where('candidato_id', '<=', 9)->get();
            $votos_del_form->map(function($voto) use (&$votos_totales) {
                $votos_totales += $voto->cantidad;
            });
        });
        foreach ($candidatos as $candidato) {
            $votos=0;
            $forms->map(function($form) use (&$votos, $candidato) {
                $votos += Vote::
                    where('formulario_id', $form->id)->
                    where('candidato_id', $candidato->id)->
                    first()->
                    cantidad;
            });
            array_push($res, [
                'nombre' => $candidato->nombre,
                'votos' => $votos_totales == 0 ? 0 : ($votos/$votos_totales * 100),
                'color' => $candidato->color,
            ]);
        }

        //votos invalidos
        $votos_totales=0;
        $votos_invalidos = 0;
        $forms->map(function($form) use (&$votos_totales, &$votos_invalidos) {
            $votos_totales_del_form = Vote::where('formulario_id', $form->id)->get();
            $votos_totales_del_form->map(function($voto) use (&$votos_totales) {
                $votos_totales += $voto->cantidad;
            });

            $votos_invalidos_del_form = Vote::where('formulario_id', $form->id)->where('candidato_id', '>', 9)->get();
            $votos_invalidos_del_form->map(function($voto) use (&$votos_invalidos) {
                $votos_invalidos += $voto->cantidad;
            });
        });

        //mesas computadas
        $mesas_total=$this->cantidad_de_mesas[$zona];
        $mesas = 0;
        $forms->map(function($form) use (&$mesas) {
            $mesas++;
        });

        //porcentaje de asistencia
        $asistencia_total=0;
        $asistencia = 0;
        $forms->map(function($form) use (&$asistencia_total, &$asistencia) {
            $asistencia_total+= $form->total_votantes;
            
            $votos = Vote::where('formulario_id', $form->id)->get();
            $votos->map(function($voto) use (&$asistencia) {
                $asistencia += $voto->cantidad;
            });
        });

        return [
            'grafico' => $res,
            'votos_nulos' => number_format($votos_totales == 0 ? 0 : $votos_invalidos / $votos_totales * 100, 2, ',', '.'). ' %',
            'mesas_computadas' => number_format($mesas_total == 0 ? 0 : $mesas / $mesas_total * 100, 2, ',', '.'). " % ($mesas de $mesas_total)",
            'asistencia' => number_format($asistencia_total == 0 ? 0 : $asistencia / $asistencia_total * 100, 2, ',', '.'). " % ($asistencia de $asistencia_total)",
        ];
    }

    public function exportar() {
        return Excel::download(new VotesExport, 'Votos RN.xls', \Maatwebsite\Excel\Excel::XLS);
    }

}
