<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados')->insert([
            ['nombre' => 'ACTIVO'],
            ['nombre' => 'INACTIVO'],
        ]);
    }
}
