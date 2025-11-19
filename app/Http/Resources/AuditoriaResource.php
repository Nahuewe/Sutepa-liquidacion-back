<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditoriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'accion'     => $this->accion,
            'modelo'     => $this->modelo,
            'modelo_id'  => $this->modelo_id,
            'datos'      => $this->datos,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user'       => [
                'id'        => $this->user->id ?? null,
                'nombre'    => $this->user->nombre ?? null,
                'apellido'  => $this->user->apellido ?? null,
                'legajo'    => $this->user->legajo ?? null,
                'dni'       => $this->user->dni ?? null,
            ],
        ];
    }
}

