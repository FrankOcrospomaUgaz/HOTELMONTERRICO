<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    protected $fillable = [
        'id',
        'descripcion',
        'estado',
        'created_at',
        'updated_at',

    ];
}
