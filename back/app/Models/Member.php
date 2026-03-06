<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_afiliado',
        'nombre',
        'dni',
        'cuil',
        'email',
        'empresa_actual',
        'fecha_afiliacion',
        'estado', // activo, jubilado, baja
        'telefono',
        'fecha_afiliacion'
    ];
}