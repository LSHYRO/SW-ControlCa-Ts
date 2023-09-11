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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id(column:'idAlumno');
            $table->string(column:'CURP')->unique()->nullable(false);
            $table->boolean(column:'estatus')->nullable(false);
            $table->foreignId(column:'idGrado')->references('idGrado')->on('grados');
            $table->foreignId(column:'idGrupo')->references('idGrupo')->on('grupos');
            $table->foreignId(column:'idPersona')->references('idPersona')->on('personas');
            $table->foreignId(column:'idMateria')->references('idMateria')->on('materias');
            $table->foreignId(column:'idTutor')->references('idTutor')->on('tutores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
