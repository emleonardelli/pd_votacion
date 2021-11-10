<?php

namespace App\Http\Controllers;

use App\Exports\VotesExport;
use App\Models\Candidate;
use App\Models\Circuit;
use App\Models\Form;
use App\Models\Vote;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormController extends Controller
{
    private $cantidad_de_mesas = [
        'Provincial'  => 1416,
        'Capital'     => 629,
        'Confluencia' => 352,
        'Oeste'       => 101,
        'Norte'       => 138,
        'Sur'         => 196,
    ];

    public function getCandidates() {
        $list = Candidate::select(['id', 'lista', 'partido_sigla', 'nombre'])->orderBy('id', 'ASC')->get();
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
        $check = Form::where('mesa', $r->mesa)->count();
        if ($check > 0) {
            return response()->json([
                'error' => 'Este formulario ya fue cargado!',
                'data' => [],
                'status' => 406
            ]);
        }

        $candidatos = Candidate::all();
        $error = true;
        $total_cargados = null;
        $candidatos->map(function($candidato) use ($r, &$error, &$total_cargados) {
            if ($r->input('candidato_'.$candidato->id)) {
                $error = false;
                $total_cargados += $r->input('candidato_'.$candidato->id);
            }
        });

        if ($error) {
            return response()->json([
                'error' => 'No tiene votos cargados!',
                'data' => [],
                'status' => 406
            ]);
        }

        if ($r->total < $total_cargados) {
            return response()->json([
                'error' => 'El total de votos es mayor al total del padron!',
                'data' => [],
                'status' => 406
            ]);
        }

        $circuito = Circuit::where('mesa', $r->mesa)->first()->circuito;
        $form = Form::create([
            'circuito' => $circuito,
            'mesa' => $r->mesa,
            'total_votantes' => $r->total,
        ]);
        $form->save();
        $candidatos->map(function($candidato) use ($r, $form) {
            Vote::insert([
                'cantidad' => $r->input('candidato_'.$candidato->id) ? $r->input('candidato_'.$candidato->id) : 0,
                'formulario_id' => $form->id,
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
                return $this->getByForm('Provincial', 0, 1597);
            break;
            case 'Capital':
                return $this->getByForm('Capital', 1, 629);
            break;
            case 'Confluencia':
                return $this->getByForm('Confluencia', 630, 989);
            break;
            case 'Oeste':
                return $this->getByForm('Oeste', 1001, 1086, 1325, 1339);
            break;
            case 'Norte':
                return $this->getByForm('Norte', 1104, 1316);
            break;
            case 'Sur':
                return $this->getByForm('Sur', 1348, 1597);
            break;
        }
    }

    private function getByForm($zona, $desde, $hasta, $desde2 = null, $hasta2 = null) {
        $candidatos = Candidate::select('id', 'color', 'nombre')->where('id', '<=', 13)->get();
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
            $votos_del_form = Vote::where('formulario_id', $form->id)->where('candidato_id', '<=', 13)->get();
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
                'votos' => $votos/$votos_totales * 100,
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

            $votos_invalidos_del_form = Vote::where('formulario_id', $form->id)->where('candidato_id', '>', 13)->get();
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
            'votos_nulos' => number_format($votos_invalidos / $votos_totales * 100, 2, ',', '.'). ' %',
            'mesas_computadas' => number_format($mesas / $mesas_total * 100, 2, ',', '.'). " % ($mesas de $mesas_total)",
            'asistencia' => number_format($asistencia / $asistencia_total * 100, 2, ',', '.'). " % ($asistencia de $asistencia_total)",
        ];
    }

    public function exportar() {
        return Excel::download(new VotesExport, 'Votos.xls', \Maatwebsite\Excel\Excel::XLS);
    }

}
