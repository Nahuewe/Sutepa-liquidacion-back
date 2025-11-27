<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'legajo',
        'username',
        'password',
        'telefono',
        'correo',
        'roles_id',
        'estados_id',
        'seccional_id',
        'login_attempts',
        'last_login_attempt',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
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
}
