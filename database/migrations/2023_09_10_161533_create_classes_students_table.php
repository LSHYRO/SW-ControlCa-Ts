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
        Schema::create('clases_alumnos', function (Blueprint $table) {
            $table->id(column:'idClaseAlumno');
            $table->foreignId(column:'idClase')->references('idClase')->on('clases');
            $table->foreignId(column:'idAlumno')->references('idAlumno')->on('alumnos');
            $table->integer(column:'calificacionClase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clases_alumnos');
    }
};
