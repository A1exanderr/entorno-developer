<?php

namespace Database\Seeders;

use App\Models\Registro\Estacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Seeder_estacion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estacion =[
            'CARRASCO',
            'YARA',
            'ALBA PATROL',
            'ADALUZ',
            'YPFB',
            'FLOR DE CAÑA',
        ];
        foreach ($estacion as $lis) {
            $new_estacion =  new Estacion();
            $new_estacion->nombre = $lis;
            $new_estacion->save();
        }
    }
}
