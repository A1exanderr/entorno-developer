<?php

namespace App\Models\Registro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    use HasFactory;
    protected $table = 'estacion';
    protected $fillable=[
        'nombre',
        'estado',
    ];
}
