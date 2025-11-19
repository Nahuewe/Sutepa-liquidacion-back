<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('conceptos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('conceptos')->insert([
            [
                'codigo'         => 'BAS',
                'descripcion'    => 'Sueldo Básico',
                'tipo'           => 'HABER',
                'monto_default'  => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'codigo'         => 'AGU',
                'descripcion'    => 'Aguinaldo',
                'tipo'           => 'HABER',
                'monto_default'  => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'codigo'         => 'AFP',
                'descripcion'    => 'Aporte Jubilatorio',
                'tipo'           => 'DESCUENTO',
                'monto_default'  => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'codigo'         => 'OBR',
                'descripcion'    => 'Obra Social',
                'tipo'           => 'DESCUENTO',
                'monto_default'  => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
