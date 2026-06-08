<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    protected $fillable = [
        'id', 
        'codigo',
         'nombre', 
         'preciocompra',
         'precioventa', 
         'unidad_id',
         'categoria_id',
         'created_at',
         'updated_at',
         'estado',
         'stock'
        ];
}
