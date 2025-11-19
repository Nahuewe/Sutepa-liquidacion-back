<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model {
    protected $fillable = ['nombre','apellido','cuil','legajo','sueldo_basico','puesto'];
    public function liquidaciones() {
        return $this->hasMany(Liquidacion::class);
    }
}
