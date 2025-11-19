<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistente extends Model
{
    protected $fillable = ['nombre', 'apellido', 'dni', 'legajo', 'seccional'];

    public function seccional()
    {
        return $this->belongsTo(Seccional::class, 'seccional_id');
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class);
    }
}
