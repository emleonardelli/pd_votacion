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
}
