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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id(column:'idAsistencia');
            $table->string(column:'asistencia');
            $table->date(column:'fecha');
            $table->foreignId(column:'idAlumno')->references('idAlumno')->on('alumnos');
            $table->foreignId(column:'idClase')->references('idClase')->on('clases');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
