<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Seeder_usuario extends Seeder
{

    //PARA LA CREACION DEL ROL


    /**
     * Run the database seeds.
     */
    public function run(): void{


        $rol1       = new Role();
        $rol1->name = 'administrador';
        $rol1->save();

        $usuario = new User();
        $usuario->usuario = 'admin';
        $usuario->password = Hash::make('rodry');
        $usuario->ci = '10028685';
        $usuario->nombres = 'Rodrigo';
        $usuario->apellidos = 'Lecoña Quispe';
        $usuario->id_persona = '0';
        $usuario->estado = 'activo';
        $usuario->save();

        $usuario->syncRoles(['administrador']);
    }



}
