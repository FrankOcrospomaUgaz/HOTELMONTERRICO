<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'id', 
        'nombres',
         'apellidopaterno', 
         'apellidomaterno',
         'dni', 
         'ruc', 
         'razonsocial', 
         'direccion', 
         'telefono',
         'email','estado',
         'created_at',
         'updated_at',
        
        ];
}
