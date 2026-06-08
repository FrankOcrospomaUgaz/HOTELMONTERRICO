<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockHabitacion extends Model
{
    protected $fillable = [
        'id',
        'cantidad',
        'producto_id',
        'habitacion_id',
        'estado',
        'created_at',
        'updated_at',
        'tipo',
    ];
}
