<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoMovimiento extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'estado',
        'created_at',
        'updated_at',
    ];
}
