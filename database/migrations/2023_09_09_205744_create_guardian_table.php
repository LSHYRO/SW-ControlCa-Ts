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
        Schema::create('tutores', function (Blueprint $table) {
            $table->id(column:'idTutor');
            $table->string(column:'apellidoP')->nullable(false);
            $table->string(column:'apellidoM')->nullable(false);
            $table->string(column:'nombre')->nullable(false);
            $table->string(column:'numTelefono')->nullable(false);
            $table->foreignId(column:'idDireccion')->references('idDireccion')->on('direcciones');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
            $table->boolean('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor');
    }
};
