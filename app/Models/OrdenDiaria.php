<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDiaria extends Model
{
    use HasFactory;

    protected $table = 'ordenes_diarias';

    protected $fillable = [
        'tipo',
        'identificador',
        'contenido',
    ];
}
