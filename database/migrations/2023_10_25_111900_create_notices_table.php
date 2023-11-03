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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id(column:'idAviso');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
            $table->string(column:'titulo');
            $table->text(column:'descripcion');
            $table->dateTime(column:'fechaHoraInicio');
            $table->dateTime(column:'fechaHoraFin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
