<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VotacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->resource->id,
            'tipo'          => $this->resource->tipo,
            'identificador' => $this->resource->identificador,
            'contenido'     => $this->resource->contenido,
            'activa_hasta'  => $this->resource->activa_hasta,
        ];
    }
}
