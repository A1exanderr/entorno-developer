<?php

namespace App\Models\Registro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;
    protected $table = 'genero';
    protected $fillable=[
        'genero',
    ];
}
