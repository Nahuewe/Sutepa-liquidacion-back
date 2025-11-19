<?php

namespace App\Exports;

use App\Models\Votacion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VotacionesExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        $votaciones = Votacion::withCount([
            'votos as afirmativos'  => fn ($q) => $q->where('respuesta', 'afirmativo'),
            'votos as negativos'    => fn ($q) => $q->where('respuesta', 'negativo'),
            'votos as abstenciones' => fn ($q) => $q->where('respuesta', 'abstencion'),
        ])->get();

        $resultado = $votaciones->map(function ($votacion) {
            $fechaHoraArg = Carbon::parse($votacion->created_at)
                                ->setTimezone('America/Argentina/Buenos_Aires');

            $counts = [
                'afirmativo' => $votacion->afirmativos  ?? 0,
                'negativo'   => $votacion->negativos    ?? 0,
                'abstencion' => $votacion->abstenciones ?? 0,
            ];
            arsort($counts);

            $maxVotes   = reset($counts);
            $topOptions = array_keys(array_filter($counts, fn ($count) => $count === $maxVotes));

            if (count($topOptions) > 1) {
                $respuestaMasVotada = 'empate';
            } else {
                $respuestaMasVotada = $topOptions[0];
            }

            return [
                'tipo'                 => $votacion->tipo                                                         ?? '-',
                'identificador'        => $votacion->identificador                                                ?? '-',
                'contenido'            => $votacion->contenido                                                    ?? '-',
                'afirmativos'          => $votacion->afirmativos                                                  ?? 0,
                'negativos'            => $votacion->negativos                                                    ?? 0,
                'abstenciones'         => $votacion->abstenciones                                                 ?? 0,
                'total_votos'          => $votacion->afirmativos + $votacion->negativos + $votacion->abstenciones,
                'respuesta_mas_votada' => $respuestaMasVotada,
                'fecha'                => $fechaHoraArg->format('d-m-Y'),
                'hora'                 => $fechaHoraArg->format('H:i'),
            ];
        });

        return $resultado->push();
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Identificador',
            'Pregunta',
            'Afirmativos',
            'Negativos',
            'Abstenciones',
            'Total de Votos',
            'Respuesta Más Votada',
            'Fecha',
            'Hora',
        ];
    }

}
