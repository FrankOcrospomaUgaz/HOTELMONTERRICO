<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rol_persona extends Model
{
    protected $fillable = [
        'id',
        'persona_id',
        'rol_id',
        'estado',
        'created_at',
        'updated_at',

    ];
}
