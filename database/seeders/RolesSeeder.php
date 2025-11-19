<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre' => 'ADMINISTRADOR'],
            ['nombre' => 'SECRETARIO'],
            ['nombre' => 'INGRESO'],
            ['nombre' => 'EGRESO'],
            ['nombre' => 'AFILIADO'],
        ]);
    }
}
