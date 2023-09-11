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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id(column:'idCalificacion');
            $table->foreignId(column:'idActividad')->references('idActividad')->on('actividades');
            $table->foreignId(column:'idAlumno')->references('idAlumno')->on('alumnos');
            $table->integer(column:'calificacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
