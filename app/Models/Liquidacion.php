<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'empleado_id',
        'mes',
        'anio',
        'total_bruto',
        'total_descuentos',
        'total_neto',
    ];

    public function conceptos()
    {
        return $this->hasMany(LiquidacionItem::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
