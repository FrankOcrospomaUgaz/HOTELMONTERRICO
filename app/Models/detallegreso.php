<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class detallegreso extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'nota',
        'tipo',
        'monto',
        'movimiento_id',
        'estado',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
