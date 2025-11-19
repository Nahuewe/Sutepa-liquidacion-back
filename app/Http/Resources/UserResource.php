<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id'           => $this->resource->id,
            'nombre'       => $this->resource->nombre,
            'apellido'     => $this->resource->apellido,
            'dni'          => $this->resource->dni,
            'legajo'       => $this->resource->legajo,
            'rol'          => $this->resource->rol->nombre,
            'roles_id'     => (int) $this->resource->roles_id,
            'seccional'    => $this->resource->seccional->nombre,
            'seccional_id' => (int) $this->resource->seccional_id,
            'creada'       => $this->resource->created_at
        ];
    }
}
