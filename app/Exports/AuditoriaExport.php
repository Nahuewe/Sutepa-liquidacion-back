<?php

namespace App\Exports;

use App\Models\Auditoria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AuditoriaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Auditoria::with('user')->get()->map(function ($item) {
            return [
                'nombre'     => $item->user->nombre ?? '',
                'apellido'   => $item->user->apellido ?? '',
                'legajo'     => $item->user->legajo ?? '',
                'dni'        => $item->user->dni ?? '',
                'accion'     => $this->formatearAccion($item->accion),
                'modelo'     => $item->modelo,
                'datos'      => $this->formatearJson($item->datos),
                'created_at' => $this->formatearFechaArgentina($item->created_at),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Legajo',
            'DNI',
            'Acción Realizada',
            'Entidad Afectada',
            'Datos Afectados',
            'Fecha y Hora',
        ];
    }

    protected function formatearJson($datos)
    {
        if (is_string($datos)) {
            $decoded = json_decode($datos, true);
        } else {
            $decoded = $datos;
        }

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $ignorar = ['id', 'created_at', 'updated_at', 'deleted_at', 'seccional_id', 'roles_id'];

            return collect($decoded)
                ->reject(fn($valor, $clave) => in_array($clave, $ignorar))
                ->map(function ($valor, $clave) {
                    if ($clave === 'activa_hasta' && !empty($valor)) {
                        try {
                            $valor = Carbon::parse($valor)
                                ->setTimezone('America/Argentina/Buenos_Aires')
                                ->format('d/m/Y H:i:s');
                        } catch (\Exception $e) {
                        }
                    }

                    if (is_array($valor)) {
                        return $clave . ': ' . json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ', ';
                    }

                    return $clave . ': ' . $valor . ', ';
                })
                ->implode("\n");
        }

        return is_string($datos) ? $datos : json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function formatearFechaArgentina($fecha)
    {
        return Carbon::parse($fecha)
            ->setTimezone('America/Argentina/Buenos_Aires')
            ->format('d/m/Y H:i:s');
    }

    protected function formatearAccion($accion)
    {
        return match ($accion) {
            'created' => 'Creación',
            'updated' => 'Modificación',
            'deleted' => 'Eliminación',
            default   => ucfirst($accion),
        };
    }
}
