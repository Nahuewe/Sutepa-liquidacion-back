<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voto extends Model
{
    use HasFactory;

    protected $fillable = [
        'votacion_id',
        'asistente_id',
        'respuesta',
    ];

    public function votacion(): BelongsTo
    {
        return $this->belongsTo(Votacion::class);
    }

    public function asistente(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
