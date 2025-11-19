<?php

namespace App\Services;

use App\Events\NuevaVotacionEvent;
use App\Models\Votacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VotacionService
{
    public function votacionLista()
    {
        $Votacion = Votacion::all();

        return $Votacion;
    }

    public function verVotacion($id)
    {
        $votacion = Votacion::where('id', $id)->first();

        return $votacion;
    }

    public function crearVotacion(array $data): Votacion
    {
        $validated = $this->validarDatos($data);

        $votacion = Votacion::create([
            ...$validated,
            'activa_hasta' => Carbon::now()->addSeconds(120),
        ]);

        broadcast(new NuevaVotacionEvent($votacion))->toOthers();

        return $votacion;
    }

    protected function validarDatos(array $data): array
    {
        $validator = Validator::make($data, [
            'tipo'          => 'required|string',
            'identificador' => 'nullable|string',
            'contenido'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
