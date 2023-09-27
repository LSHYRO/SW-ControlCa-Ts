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
        Schema::create('personas', function (Blueprint $table) {
            $table->id(column:'idPersona');
            $table->string(column:'apellidoP')->nullable(false);
            $table->string(column:'apellidoM')->nullable(false);
            $table->string(column:'nombre')->nullable(false);
            $table->date(column:'fechaNacimiento')->nullable(false);
            $table->timestamps();            
            $table->softDeletes();
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
