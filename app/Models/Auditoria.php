<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Auditoria extends Model
{
    protected $fillable = ['user_id', 'accion', 'modelo', 'modelo_id', 'datos'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
