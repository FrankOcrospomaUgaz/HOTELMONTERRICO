<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class motivosDocAlmacen extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'tipo',
        'estado',
        'created_at',
        'updated_at',
    ];
}
