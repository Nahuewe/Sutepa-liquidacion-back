<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccional extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'seccional';

    protected $guarded = [];

    public function usuario(): HasMany
    {
        return $this->hasMany(User::class, 'seccional_id');
    }

}
