<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('empleados')->insert([
            [
                'nombre'        => 'Juan',
                'apellido'      => 'Pérez',
                'cuil'          => '20-12345678-3',
                'legajo'        => '1001',
                'sueldo_basico' => 250000,
                'puesto'        => 'Administrativo',
            ],
            [
                'nombre'        => 'María',
                'apellido'      => 'Gómez',
                'cuil'          => '27-87654321-9',
                'legajo'        => '1002',
                'sueldo_basico' => 310000,
                'puesto'        => 'Contadora',
            ],
            [
                'nombre'        => 'Carlos',
                'apellido'      => 'Martínez',
                'cuil'          => '20-33445566-7',
                'legajo'        => '1003',
                'sueldo_basico' => 280000,
                'puesto'        => 'Operario',
            ],
        ]);
    }
}
