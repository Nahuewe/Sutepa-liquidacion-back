<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('seccional')->insert([
            ['nombre' => 'LANUS'],
            ['nombre' => 'BAHIA BLANCA'],
            ['nombre' => 'CAPITAL FEDERAL'],
            ['nombre' => 'CORDOBA'],
            ['nombre' => 'CORRIENTES'],
            ['nombre' => 'CHUBUT'],
            ['nombre' => 'DAMNPYP'],
            ['nombre' => 'CONCORDIA'],
            ['nombre' => 'LA PLATA'],
            ['nombre' => 'LA PAMPA'],
            ['nombre' => 'LUJAN'],
            ['nombre' => 'MENDOZA'],
            ['nombre' => 'MILSTEIN'],
            ['nombre' => 'MISIONES'],
            ['nombre' => 'ROSARIO'],
            ['nombre' => 'SALTA'],
            ['nombre' => 'SAN JUSTO'],
            ['nombre' => 'SAN JUAN'],
            ['nombre' => 'TUCUMAN'],
            ['nombre' => 'CHIVILCOY'],
            ['nombre' => 'AZUL'],
            ['nombre' => 'NACIONAL'],
            ['nombre' => 'NEUQUEN'],
            ['nombre' => 'CATAMARCA'],
            ['nombre' => 'CHACO'],
            ['nombre' => 'TIERRA DEL FUEGO'],
            ['nombre' => 'SAN MARTIN'],
            ['nombre' => 'JUJUY'],
            ['nombre' => 'FORMOSA'],
            ['nombre' => 'MAR DEL PLATA'],
        ]);
    }
}
