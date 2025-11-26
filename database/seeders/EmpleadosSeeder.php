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
                'cuil'          => '20123456783',
                'legajo'        => '1001',
                'sueldo_basico' => 250000,
                'puesto'        => 'ADMINISTRATIVO',
            ],
            [
                'nombre'        => 'María',
                'apellido'      => 'Gómez',
                'cuil'          => '27876543219',
                'legajo'        => '1002',
                'sueldo_basico' => 310000,
                'puesto'        => 'SUPERVISOR',
            ],
            [
                'nombre'        => 'Carlos',
                'apellido'      => 'Martínez',
                'cuil'          => '20334455667',
                'legajo'        => '1003',
                'sueldo_basico' => 280000,
                'puesto'        => 'OPERARIO',
            ],
        ]);
    }
}
