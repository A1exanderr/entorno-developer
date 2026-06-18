<?php

namespace App\Models\Registro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona';
    protected $fillable=[
        'ci',
        'complemento',
        'nombres',
        'ap_paterno',
        'ap_materno',
        'fecha_nacimiento',
        'celular',
        'direccion',
        'estado',
        'id_genero',
        'id_usuario',
    ];

    //relacion reversa con genero
    public function genero(){
        return $this->belongsTo(Genero::class ,'id_genero', 'id');
    }
}
