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

        /*
        Formulario carga de votos Presidencial
        132 – JxC / Patricia Bullrich			color amarillo oscuro
        133 – Hacemos / Juan Scharetti		color anaranjado
        134 – UP / Sergio Massa			color celeste
        135 – LLA / Javier Milei			color violeta
        136 – FIT / Myriam Bregman			color rojo

        Formulario carga de votos Diputados
        77 – LLA_FE / Lorena Villaverde		color violeta
        501 – JSRN / Luis Di Giacomo		color verde
        502 – JxC / Sergio Capozzi			color amarillo oscuro
        503 – UP / Martín Soria			color celeste
        504 – FIT / Alhue Gavuzzo			color rojo
        */
        Candidate::insert(['id' => 1, 'eleccion' => 'presidente', 'color' => '#5b9bd5', 'lista' => '134','partido_sigla' => 'UP','nombre' => 'Sergio Massa']);
        Candidate::insert(['id' => 2, 'eleccion' => 'presidente', 'color' => '#7030a0', 'lista' => '135','partido_sigla' => 'LLA','nombre' => 'Javier Milei']);
        
        /*
        Candidate::insert(['id' => 1, 'eleccion' => 'presidente', 'color' => '#FFFF00', 'lista' => '132','partido_sigla' => 'JxC','nombre' => 'Patricia Bullrich']);
        Candidate::insert(['id' => 2, 'eleccion' => 'presidente', 'color' => '#ff6600', 'lista' => '133','partido_sigla' => 'Hacemos','nombre' => 'Juan Scharetti']);
        Candidate::insert(['id' => 5, 'eleccion' => 'presidente', 'color' => '#ff0000', 'lista' => '136','partido_sigla' => 'FIT','nombre' => 'Myriam Bregman']);
        Candidate::insert(['id' => 6, 'eleccion' => 'presidente', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos en Blancos']);
        Candidate::insert(['id' => 7, 'eleccion' => 'presidente', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Nulos']);
        Candidate::insert(['id' => 8, 'eleccion' => 'presidente', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Recurridos']);
        Candidate::insert(['id' => 9, 'eleccion' => 'presidente', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Impugnados']);

        Candidate::insert(['id' => 10, 'eleccion' => 'diputado', 'color' => '#7030a0', 'lista' => '77','partido_sigla' => 'LLA_FE','nombre' => 'Lorena Villaverde']);
        Candidate::insert(['id' => 11, 'eleccion' => 'diputado', 'color' => '#009A3B', 'lista' => '501','partido_sigla' => 'JSRN','nombre' => 'Luis Di']);
        Candidate::insert(['id' => 12, 'eleccion' => 'diputado', 'color' => '#FFFF00', 'lista' => '502','partido_sigla' => 'JxC','nombre' => 'Sergio Capozzi']);
        Candidate::insert(['id' => 13, 'eleccion' => 'diputado', 'color' => '#5b9bd5', 'lista' => '503','partido_sigla' => 'UP','nombre' => 'Martín Soria']);
        Candidate::insert(['id' => 14, 'eleccion' => 'diputado', 'color' => '#ff0000', 'lista' => '504','partido_sigla' => 'FIT','nombre' => 'Alhue Gavuzzo']);
        Candidate::insert(['id' => 15, 'eleccion' => 'diputado', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos en Blancos']);
        Candidate::insert(['id' => 16, 'eleccion' => 'diputado', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Nulos']);
        Candidate::insert(['id' => 17, 'eleccion' => 'diputado', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Recurridos']);
        Candidate::insert(['id' => 18, 'eleccion' => 'diputado', 'color' => '#', 'lista' => '0','partido_sigla' => '0','nombre' => 'Votos Impugnados']);
        */
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
