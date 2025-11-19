<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class LiquidacionesSeeder extends Seeder
{
    public function run(): void
    {
        $empleados = Empleado::all();

        foreach ($empleados as $empleado) {

            $total_haberes = $empleado->sueldo_basico;
            $total_descuentos = $empleado->sueldo_basico * 0.17;
            $neto = $total_haberes - $total_descuentos;

            DB::table('liquidaciones')->insert([
                'empleado_id'      => $empleado->id,
                'periodo'          => '2025-11',
                'total_haberes'    => $total_haberes,
                'total_descuentos' => $total_descuentos,
                'neto'             => $neto,
                'estado'           => 'PENDIENTE',
                'metadata'         => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
