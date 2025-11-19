<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LiquidacionItem extends Model {
    protected $fillable = ['liquidacion_id','concepto_id','tipo','codigo','descripcion','monto'];
    public function concepto() {
        return $this->belongsTo(Concepto::class);
    }
}
