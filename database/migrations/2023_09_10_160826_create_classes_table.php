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
        Schema::create('clases', function (Blueprint $table) {
            $table->id(column:'idClase');
            $table->time(column:'hora');
            $table->string(column:'dias');
            $table->foreignId(column:'idGrupo')->references('idGrupo')->on('grupos');
            $table->foreignId(column:'idGrado')->references('idGrado')->on('grados');
            $table->foreignId(column:'idPersonal')->references('idPersonal')->on('personal');
            $table->foreignId(column:'idMateria')->references('idMateria')->on('materias');
            $table->foreignId(column:'idCiclo')->references('idCiclo')->on('ciclos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
