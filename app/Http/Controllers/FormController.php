<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Form;
use App\Models\Vote;
use Illuminate\Http\Request;

class FormController extends Controller
{
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

        $form = Form::create([
            'circuito' => $r->circuito,
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
            case 'General':
                return $this->getByForm(0, 1650);
            break;
            case 'Capital':
                return $this->getByForm(01, 89);
            break;
            case 'Confluencia':
                return $this->getByForm(100, 190);
            break;
            case 'Oeste':
                return $this->getByForm(200, 290, 900, 920);
            break;
            case 'Norte':
                return $this->getByForm(300, 860);
            break;
            case 'Sur':
                return $this->getByForm(1000, 1650);
            break;
        }
    }

    private function getByForm($desde, $hasta, $desde2 = null, $hasta2 = null) {
        $candidatos = Candidate::select('id', 'color', 'nombre')->where('id', '<=', 13)->get();
        $forms = [];
        if ($desde2) {
            $forms_a=Form::where('circuito', '>=', $desde)->where('circuito', '<=', $hasta);
            $forms=Form::where('circuito', '>=', $desde2)->where('circuito', '<=', $hasta2)->union($forms_a)->get();
        }else{
            $forms=Form::where('circuito', '>=', $desde)->where('circuito', '<=', $hasta)->get();
        }
        $res = [];
        $votos_totales=0;
        $forms->map(function($form) use (&$votos_totales) {
            $votos_del_form = Vote::where('formulario_id', $form->id)->get();
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
        return $res;
    }

}
