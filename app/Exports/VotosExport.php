<?php

namespace App\Exports;

use App\Models\Voto;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VotosExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Voto::with([
                'votacion',
                'asistente.seccional',
            ])
            ->get()
            ->map(function ($voto) {
                $dniClean = str_replace('.', '', $voto->asistente->dni ?? '');

                $fechaHoraArg = Carbon::parse($voto->created_at)
                                    ->setTimezone('America/Argentina/Buenos_Aires');

                return [
                    'tipo_votacion' => $voto->votacion->tipo          ?? '-',
                    'identificador' => $voto->votacion->identificador ?? '-',
                    'contenido'     => $voto->votacion->contenido     ?? '-',
                    'respuesta'     => ucfirst($voto->respuesta)      ?? '-',
                    'apellido'      => $voto->asistente->apellido     ?? '-',
                    'nombre'        => $voto->asistente->nombre       ?? '-',
                    'dni'           => $dniClean,
                    'legajo'        => $voto->asistente->legajo            ?? '-',
                    'seccional'     => $voto->asistente->seccional->nombre ?? '-',
                    'fecha'         => $fechaHoraArg->format('d-m-Y'),
                    'hora'          => $fechaHoraArg->format('H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tipo de Votación',
            'Identificador',
            'Pregunta',
            'Respuesta',
            'Apellido',
            'Nombre',
            'DNI',
            'Legajo',
            'Seccional',
            'Fecha',
            'Hora',
        ];
    }
}
