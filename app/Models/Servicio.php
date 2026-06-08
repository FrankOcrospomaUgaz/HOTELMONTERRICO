<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicio extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'precioventa',
        'estado',
        'created_at',
        'updated_at',
        'tipo',
    ];
}
