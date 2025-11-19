<?php

// app/Services/LiquidacionService.php
namespace App\Services;

use App\Models\Liquidacion;
use App\Models\LiquidacionItem;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class LiquidacionService
{
    public function create(array $payload): Liquidacion
    {
        return DB::transaction(function () use ($payload) {
            $empleado = Empleado::findOrFail($payload['empleado_id']);

            $liquidacion = Liquidacion::create([
                'empleado_id' => $empleado->id,
                'periodo' => $payload['periodo'],
                'total_haberes' => 0,
                'total_descuentos' => 0,
                'neto' => 0,
                'estado' => 'PENDIENTE',
            ]);

            $totalHaberes = 0;
            $totalDescuentos = 0;

            foreach ($payload['items'] as $it) {
                $monto = (float) $it['monto'];
                $tipo = $it['tipo'];

                LiquidacionItem::create([
                    'liquidacion_id' => $liquidacion->id,
                    'concepto_id' => $it['concepto_id'] ?? null,
                    'tipo' => $tipo,
                    'codigo' => $it['codigo'] ?? null,
                    'descripcion' => $it['descripcion'] ?? null,
                    'monto' => $monto,
                ]);

                if ($tipo === 'HABER') $totalHaberes += $monto;
                else $totalDescuentos += $monto;
            }

            $neto = $totalHaberes - $totalDescuentos;

            $liquidacion->update([
                'total_haberes' => $totalHaberes,
                'total_descuentos' => $totalDescuentos,
                'neto' => $neto,
            ]);

            return $liquidacion->load('items','empleado');
        });
    }

    public function markAsPaid(Liquidacion $liquidacion)
    {
        $liquidacion->update([
            'estado' => 'PAGADA',
            'pagada_at' => now(),
        ]);
    }
}
