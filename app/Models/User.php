<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'legajo',
        'roles_id',
        'seccional_id',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol()
    {
        return $this->belongsTo(Roles::class, 'roles_id');
    }

    public function seccional()
    {
        return $this->belongsTo(Seccional::class, 'seccional_id');
    }

    public function votos()
    {
        return $this->hasMany(Voto::class, 'asistente_id');
    }
}
