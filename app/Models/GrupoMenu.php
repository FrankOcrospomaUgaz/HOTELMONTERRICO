<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMenu extends Model
{
    protected $fillable = [
        'id', 
        'nombre',
         'icono', 
         'estado',
         'created_at',
         'updated_at',
        ];
}
