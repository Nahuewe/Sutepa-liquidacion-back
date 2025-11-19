<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EgresosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('asistentes')
            ->join('egresos', 'asistentes.id', '=', 'egresos.asistente_id')
            ->select([
                'asistentes.apellido',
                'asistentes.nombre',
                'asistentes.dni',
                'asistentes.legajo',
                'asistentes.seccional',
                'egresos.registrado_en',
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
            'Fecha de Egreso',
            'Hora de Egreso',
        ];
    }
}
