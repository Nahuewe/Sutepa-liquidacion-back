<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $fillable = ['asistente_id', 'registrado_en'];

    public function asistente()
    {
        return $this->belongsTo(Asistente::class);
    }
}
