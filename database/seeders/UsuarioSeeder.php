<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'nombre'       => 'NAHUEL',
                'apellido'     => 'SORIA PARODI',
                'dni'          => '43.532.773',
                'legajo'       => '99999',
                'roles_id'     => 1,
                'seccional_id' => 24,
            ]
        ]);
    }
}
