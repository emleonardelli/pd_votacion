<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Candidate::insert(['id' => 1,  'color' => '#FF6600', 'lista' => '50','partido_sigla' => 'PS','nombre' => 'Paolo Echepareborda']);
        Candidate::insert(['id' => 2,  'color' => '#FF6961', 'lista' => '165','partido_sigla' => 'MAS','nombre' => 'Elena Correa']);
        Candidate::insert(['id' => 3,  'color' => '#00AAE4', 'lista' => '504','partido_sigla' => 'Fdt','nombre' => 'Ana Marks']);
        Candidate::insert(['id' => 4,  'color' => '#008F39', 'lista' => '502','partido_sigla' => 'JSRN','nombre' => 'Agustín Domingo']);
        Candidate::insert(['id' => 5,  'color' => '#8B0000', 'lista' => '503 A','partido_sigla' => 'FIT','nombre' => 'Norma Dardik']);
        Candidate::insert(['id' => 6,  'color' => '#8B0000', 'lista' => '503 R','partido_sigla' => 'FIT','nombre' => 'Jorge Paulic']);
        Candidate::insert(['id' => 7,  'color' => '#FFFF00', 'lista' => '504 A','partido_sigla' => 'JxC','nombre' => 'Germán Jalabert']);
        Candidate::insert(['id' => 8,  'color' => '#FFFF00', 'lista' => '504 B','partido_sigla' => 'JxC','nombre' => 'Anibal Tortoriello']);
        Candidate::insert(['id' => 9,  'color' => '#FFFF00', 'lista' => '504 C','partido_sigla' => 'JxC','nombre' => 'Mario De Rege']);
        Candidate::insert(['id' => 10, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos en Blancos']);
        Candidate::insert(['id' => 11, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Nulos']);
        Candidate::insert(['id' => 12, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Recurridos']);
        Candidate::insert(['id' => 13, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Impugnados']);

        // 50          PS      Paola Echepareborda
        // 165         MAS     Elena Correa
        // 504         Fdt     Ana Marks
        // 502         JSRN    Agustín Domingo
        // 503 A       FIT     Norma Dardik
        // 503 R       FIT     Jorge Paulic
        // 504 A       JxC     Germán Jalabert
        // 504 B       JxC     Anibal Tortoriello
        // 504 C       JxC     Mario De Rege
    }
}
