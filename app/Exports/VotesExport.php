<?php

namespace App\Exports;

use App\Models\Candidate;
use App\Models\Form;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class VotesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $forms = Form::all();
        
        $headers = [
            'Formulario ID', 'Circuito', 'Mesa', 'Total Votantes',
        ];
        
        $candidates = Candidate::select('id', 'nombre')->orderBy('id', 'ASC')->get();
        $candidates->map(function($candidato) use (&$headers) {
            array_push($headers, $candidato->nombre);
        });

        $res = [];

        $forms->map(function($form) use (&$res, $candidates) {
            $temp_row=[
                $form->id, $form->circuito, $form->mesa, $form->total_votantes
            ];
            $candidates->map(function($candidato) use (&$temp_row, $form) {
                $vote = Vote::
                    where('formulario_id', $form->id)
                    ->where('candidato_id', $candidato->id)
                    ->first();
                $vote = $vote ? $vote->cantidad == 0 ? '0' : $vote->cantidad : '0';
                array_push($temp_row, $vote);
            }); 
            array_push($res, $temp_row);
        });

        return new Collection([
            $headers,
            $res
        ]);
    }
}
