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
        Candidate::insert(['lista' => '40','partido_sigla' => 'LDS','nombre' => 'Jesus Escobar']);
        Candidate::insert(['lista' => '47','partido_sigla' => 'CC-ARI','nombre' => 'Carlos Eguia']);
        Candidate::insert(['lista' => '50','partido_sigla' => 'PS','nombre' => 'Sandrita Ferrero']);
        Candidate::insert(['lista' => '151 A','partido_sigla' => 'MPN','nombre' => 'Mage Ferraresso']);
        Candidate::insert(['lista' => '151 B','partido_sigla' => 'MPN','nombre' => 'Hugo Rauque']);
        Candidate::insert(['lista' => '151 F','partido_sigla' => 'MPN','nombre' => 'Rolo Figueroa']);
        Candidate::insert(['lista' => '186','partido_sigla' => 'MAS','nombre' => 'Lucas Ruiz']);
        Candidate::insert(['lista' => '501','partido_sigla' => 'FdT','nombre' => 'Mirás Trabalón']);
        Candidate::insert(['lista' => '501','partido_sigla' => 'FdT','nombre' => 'Fabian Ungar']);
        Candidate::insert(['lista' => '501','partido_sigla' => 'FdT','nombre' => 'Tanya Bertoldi']);
        Candidate::insert(['lista' => '502','partido_sigla' => 'FIT','nombre' => 'Priscila Ottón']);
        Candidate::insert(['lista' => '502','partido_sigla' => 'FIT','nombre' => 'Raúl Godoy']);
        Candidate::insert(['lista' => '503','partido_sigla' => 'CN','nombre' => 'Pablo Cervi']);
        Candidate::insert(['lista' => '0','partido_sigla' => '0','nombre' => 'Votos en Blancos']);
        Candidate::insert(['lista' => '0','partido_sigla' => '0','nombre' => 'Votos Nulos / Recurridos']);

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
