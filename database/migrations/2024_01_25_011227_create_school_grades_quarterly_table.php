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
        Schema::create('calificaciones_periodos', function (Blueprint $table) {
            $table->id(column:"idCalificacionPeriodo");
            $table->foreignId(column:"idClase")->references("idClase")->on("clases");
            $table->foreignId(column:"idAlumno")->references("idAlumno")->on("alumnos");
            $table->foreignId(column:"idPeriodo")->references("idPeriodo")->on("periodos");
            $table->double("calificacion", 5, 2)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_grades_quarterly');
    }
};
