<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class movimiento extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'numero',
        'fecha',
        'fechaingreso',
        'turno',
        'fechasalida',
        'persona_id',
        'tipodocumento_id',
        'conceptopago_id',
        'vuelto',
        'total',
        'efectivo',
        'tarjeta',
        'yape',
        'deposito',
        'comentario',
        'plin',
        'situacion',
        'operacion',
        'formaPago',
        'cantCuotas',
        'movimiento_id',
        'habitacion_id',
        'usuario_id',

        'estado',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function moviment_venta()
    {
        return $this->hasOne(Movimiento::class);
    }
}
