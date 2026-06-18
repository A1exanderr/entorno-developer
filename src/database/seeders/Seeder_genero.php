<?php

namespace Database\Seeders;

use App\Models\Registro\Genero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Seeder_genero extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            [
                'sigla'=>'M',
                'genero'=>'Masculino'
            ],
            [
                'sigla'=>'F',
                'genero'=>'Femenino'
            ]
        ];
        foreach ($generos as $lis) {
            $genero = new Genero();
            $genero->sigla = $lis['sigla'];
            $genero->genero = $lis['genero'];
            $genero->save();
        }
    }
}
