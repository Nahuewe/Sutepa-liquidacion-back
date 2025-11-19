<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IngresosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('asistentes')
            ->join('ingresos', 'asistentes.id', '=', 'ingresos.asistente_id')
            ->select([
                'asistentes.apellido',
                'asistentes.nombre',
                'asistentes.dni',
                'asistentes.legajo',
                'asistentes.seccional',
                'ingresos.registrado_en',
            ])
            ->get()
            ->map(function ($item) {
                $item->dni = str_replace('.', '', $item->dni);

                $fechaHora   = \Carbon\Carbon::parse($item->registrado_en);
                $item->fecha = $fechaHora->format('d-m-Y');
                $item->hora  = $fechaHora->format('H:i');

                unset($item->registrado_en);

                return $item;
            });
    }

    public function headings(): array
    {
        return [
            'Apellido',
            'Nombre',
            'DNI',
            'Legajo',
            'Seccional',
            'Fecha de Ingreso',
            'Hora de Ingreso',
        ];
    }
}
