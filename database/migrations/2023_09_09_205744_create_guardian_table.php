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
            $table->id(column: 'idTutor');
            $table->string(column: 'apellidoP')->nullable(false);
            $table->string(column: 'apellidoM')->nullable(false);
            $table->string(column: 'nombre')->nullable(false);
            $table->string(column: 'numTelefono')->nullable(false);
            $table->string(column:'correoElectronico')->nullable(false);
            $table->foreignId(column:'idGenero')->references('idGenero')->on('generos');
            $table->foreignId(column: 'idDireccion')->references('idDireccion')->on('direcciones');
            $table->foreignId(column: 'idUsuario')->references('idUsuario')->on('usuarios');
            $table->text('nombre_completo')->nullable()->fulltext();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutores');
    }
};
