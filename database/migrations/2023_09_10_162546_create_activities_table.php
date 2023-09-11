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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id(column:'idActividad');
            $table->string(column:'descripcion');
            $table->foreignId(column:'idClase')->references('idClase')->on('clases');
            $table->foreignId(column:'idPeriodo')->references('idPeriodo')->on('periodos');
            $table->foreignId(column:'idTipoActividad')->references('idTipoActividad')->on('tiposActividades');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
