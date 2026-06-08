<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class habitacion extends Model
{
    protected $fillable = [
        'id',
        'numero',
        'situacion',
        'horaInicio',
        'horaFin',
        'total',

        'estado',
        'created_at',
        'updated_at',
        'tipo',
        'idUltimoMovimiento',
    ];
}
