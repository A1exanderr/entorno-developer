<?php

namespace App\Models\Registro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Registro\Estacion;
use App\Models\Registro\Persona;
use App\Models\User;

class Compra extends Model
{
    use HasFactory;
    protected $table = 'compra';
    protected $fillable=[
        'fecha_compra',
        'cantidad_litros',
        'id_estacion',
        'id_persona',
        'id_usuario'
    ];

    //relacion reversa con estacion
    public function estacion(){
        return $this->belongsTo(Estacion::class,'id_estacion', 'id');
    }
    //relacion reversa con persona
    public function persona(){
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }


    //para la parte de usuario
    public function usuario(){
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

}
