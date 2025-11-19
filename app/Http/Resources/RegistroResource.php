<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'asistente_id'  => $this->asistente_id,
            'registrado_en' => $this->registrado_en,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'asistente'     => [
                'id'        => $this->asistente->id,
                'nombre'    => $this->asistente->nombre,
                'apellido'  => $this->asistente->apellido,
                'dni'       => $this->asistente->dni,
                'legajo'    => $this->asistente->legajo ?? null,
                'seccional' => $this->asistente->seccional
            ],
        ];
    }
}
