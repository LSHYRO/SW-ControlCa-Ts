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
        Schema::create('grado_grupo_alumno', function (Blueprint $table) {
            $table->id(column:'idGradGrupAl');
            $table->foreignId(column:"idAlumno")->references("idAlumno")->on("alumnos");
            $table->foreignId(column:'idGrado')->references('idGrado')->on("grados");
            $table->foreignId(column:'idGrupo')->references('idGrupo')->on("grupos");
            $table->foreignId(column:'idCiclo')->references('idCiclo')->on("ciclos");
            $table->integer(column:'calificacion')->nullable();
            $table->boolean(column: 'estatus')->default(true); //Activo o Inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grado_grupo_alumno');
    }

};
