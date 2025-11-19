<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'empleado_id',
        'periodo',          
        'total_haberes',
        'total_descuentos',
        'neto',
        'estado',
        'pagada_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'pagada_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(LiquidacionItem::class, 'liquidacion_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
