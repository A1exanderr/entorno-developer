<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('genero', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 10)->unique();
            $table->string('genero', 50)->unique();
            $table->timestamps();
        });

        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 20)->unique();
            $table->string('complemento')->nullable();
            $table->string('nombres', 100);
            $table->string('ap_paterno', 100);
            $table->string('ap_materno', 100)->nullable();
            $table->date('fecha_nacimiento');
            $table->string('celular', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->string('estado');
            $table->unsignedBigInteger('id_genero');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_genero')
                ->references('id')
                ->on('genero')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
