<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Votacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'identificador',
        'contenido',
        'activa_hasta',
    ];

    protected $casts = [
        'activa_hasta' => 'datetime',
    ];

    public function votos(): HasMany
    {
        return $this->hasMany(Voto::class);
    }
}
