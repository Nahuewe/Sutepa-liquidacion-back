<?php

namespace App\Services;

use App\Events\VotoRegistradoEvent;
use App\Models\Votacion;
use App\Models\Voto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VotoService
{
    public function votoLista()
    {
        return Voto::all();
    }

    public function verVoto($id)
    {
        $voto = Voto::where('id', $id)->first();

        return $voto;
    }

    public function registrarVoto(array $data): Voto
    {
        $validated = $this->validarDatos($data);

        $votacion = Votacion::findOrFail($validated['votacion_id']);

        $voto = Voto::create($validated);

        $this->emitirConteoActualizado($votacion);

        return $voto;
    }

    protected function validarDatos(array $data): array
    {
        $validator = Validator::make($data, [
            'votacion_id'  => 'required|exists:votacions,id',
            'respuesta'    => 'required|in:afirmativo,negativo,abstencion',
            'asistente_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    protected function emitirConteoActualizado(Votacion $votacion): void
    {
        $conteo = [
            'afirmativo' => $votacion->votos()->where('respuesta', 'afirmativo')->count(),
            'negativo'   => $votacion->votos()->where('respuesta', 'negativo')->count(),
            'abstencion' => $votacion->votos()->where('respuesta', 'abstencion')->count(),
        ];

        broadcast(new VotoRegistradoEvent($votacion, $conteo))->toOthers();
    }
}
