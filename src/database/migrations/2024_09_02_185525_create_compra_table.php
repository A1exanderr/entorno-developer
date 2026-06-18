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
        Schema::create('compra', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_compra');
            $table->decimal('cantidad_litros', 5, 2);
            $table->unsignedBigInteger('id_estacion');
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            //llaves foraneas
            $table->foreign('id_estacion')
                ->references('id')
                ->on('estacion')
                ->onDelete('restrict');
            $table->foreign('id_persona')
                ->references('id')
                ->on('persona')
                ->onDelete('restrict');
            $table->foreign('id_usuario')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra');
    }
};
