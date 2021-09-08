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
        $form = Form::create([
            'circuito' => $r->circuito,
            'mesa' => $r->mesa,
            'total_votantes' => $r->total,
        ]);
        $form->save();
        $candidatos = Candidate::all();
        $candidatos->map(function($candidato) use ($r, $form) {
            Vote::insert([
                'cantidad' => $r->input('candidato_'.$candidato->id),
                'formulario_id' => $form->id,
                'candidato_id' => $candidato->id,
            ]);
        });
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
            case 'Este':
                $colors = ['#C0392B','#C0392B','#F1948A','#9B59B6','#BB8FCE','#2980B9','#AED6F1','#148F77','#A3E4D7','#B7950B','#F39C12','#D35400','#34495E','#F0B27A','#9C640C','#D5DBDB',];
                $candidatos = Candidate::select('id', 'nombre')->get();
                $res = [];
                foreach ($candidatos as $candidato) {
                    $forms_a=Form::where('circuito', '>=', 200)->where('circuito', '<=', 290);
                    $forms=Form::where('circuito', '>=', 900)->where('circuito', '<=', 920)->union($forms_a)->get();
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
                        'votos' => $votos,
                        'color' => $colors[$candidato->id],
                    ]);
                }
                return $res;
            break;
            case 'Norte':
                return $this->getByForm(300, 860);
            break;
            case 'Sur':
                return $this->getByForm(1000, 1650);
            break;
        }
    }

    private function getByForm($desde, $hasta) {
        $colors = ['#C0392B','#C0392B','#F1948A','#9B59B6','#BB8FCE','#2980B9','#AED6F1','#148F77','#A3E4D7','#B7950B','#F39C12','#D35400','#34495E','#F0B27A','#9C640C','#D5DBDB',];
        $candidatos = Candidate::select('id', 'nombre')->get();
        $res = [];
        foreach ($candidatos as $candidato) {
            $forms=Form::where('circuito', '>=', $desde)->where('circuito', '<=', $hasta)->get();
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
                'votos' => $votos,
                'color' => $colors[$candidato->id],
            ]);
        }
        return $res;
    }

}
