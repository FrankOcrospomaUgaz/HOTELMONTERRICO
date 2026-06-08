<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class detallemovimiento extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'cantidad',
        'preciocompra',
        'precioventa',
        'descuento',
        'comentario',
        'servicio_id',
        'producto_id',
        'movimiento_id',
        'motivos_doc_almacens_id',
        'estado',
        'tipo',
        'created_at',
        'updated_at',
    ];
}
