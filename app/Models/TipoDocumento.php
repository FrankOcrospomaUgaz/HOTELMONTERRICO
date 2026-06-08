<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoDocumento extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'tipomovimiento_id',
        'estado',
        'created_at',
        'updated_at',
    ];
}
