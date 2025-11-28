<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nombre'       => $this->nombre,
            'apellido'     => $this->apellido,
            'dni'          => $this->dni,
            'legajo'       => $this->legajo,
            'username'     => $this->username,
            'telefono'     => $this->telefono,
            'correo'       => $this->correo,
            'rol'          => $this->rol->nombre ?? null,
            'roles_id'     => (int) $this->roles_id,
            'seccional'    => $this->seccional->nombre ?? null,
            'seccional_id' => (int) $this->seccional_id,
            'estado'       => $this->estado->nombre ?? null,
            'creada'       => $this->created_at,
        ];
    }
}
