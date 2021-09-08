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
        Candidate::insert(['id' => 1,  'color' => '#800040', 'lista' => '40','partido_sigla' => 'LDS','nombre' => 'Jesus Escobar']);
        Candidate::insert(['id' => 2,  'color' => '#FF8000', 'lista' => '47','partido_sigla' => 'CC-ARI','nombre' => 'Carlos Eguia']);
        Candidate::insert(['id' => 3,  'color' => '#008F39', 'lista' => '50','partido_sigla' => 'PS','nombre' => 'Sandrita Ferrero']);
        Candidate::insert(['id' => 4,  'color' => '#2944DF', 'lista' => '151 A','partido_sigla' => 'MPN','nombre' => 'Mage Ferraresso']);
        Candidate::insert(['id' => 5,  'color' => '#2944DF', 'lista' => '151 B','partido_sigla' => 'MPN','nombre' => 'Hugo Rauque']);
        Candidate::insert(['id' => 6,  'color' => '#2944DF', 'lista' => '151 F','partido_sigla' => 'MPN','nombre' => 'Rolo Figueroa']);
        Candidate::insert(['id' => 7,  'color' => '#FF6961', 'lista' => '183','partido_sigla' => 'MAS','nombre' => 'Lucas Ruiz']);
        Candidate::insert(['id' => 8,  'color' => '#00AAE4', 'lista' => '501','partido_sigla' => 'FdT','nombre' => 'Tanya Bertoldi']);
        Candidate::insert(['id' => 9,  'color' => '#00AAE4', 'lista' => '501','partido_sigla' => 'FdT','nombre' => 'Mirás Trabalón']);
        Candidate::insert(['id' => 10, 'color' => '#00AAE4', 'lista' => '501','partido_sigla' => 'FdT','nombre' => 'Fabian Ungar']);
        Candidate::insert(['id' => 11, 'color' => '#CB3234', 'lista' => '502','partido_sigla' => 'FIT','nombre' => 'Priscila Ottón']);
        Candidate::insert(['id' => 12, 'color' => '#CB3234', 'lista' => '502','partido_sigla' => 'FIT','nombre' => 'Raúl Godoy']);
        Candidate::insert(['id' => 13, 'color' => '#FFFF00', 'lista' => '503','partido_sigla' => 'CN','nombre' => 'Pablo Cervi']);
        Candidate::insert(['id' => 14, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos en Blancos']);
        Candidate::insert(['id' => 15, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Nulos']);
        Candidate::insert(['id' => 16, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Recurridos']);
        Candidate::insert(['id' => 17, 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Impugnados']);

        // 40       LDS       Jesus Escobar
        // 47       CC-ARI    Carlos Eguia
        // 50       PS        Sandrita Ferrero
        // 151 A    MPN       Mage Ferraresso
        // 151 B    MPN       Hugo Rauque
        // 151 F    MPN       Rolo Figueroa
        // 186      MAS       Lucas Ruiz
        // 501      FdT       Mirás Trabalón
        // 501      FdT       Fabian Ungar
        // 501      FdT       Tanya Bertoldi
        // 502      FIT       Priscila Ottón
        // 502      FIT       Raúl Godoy
        // 503      CN        Pablo Cervi
        // 0        0         Votos en Blancos
        // 0        0         Votos Nulos / Recurridos
    }
}
