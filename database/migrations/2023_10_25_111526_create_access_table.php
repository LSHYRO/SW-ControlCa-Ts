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
        Schema::create('accesos', function (Blueprint $table) {
            $table->id(column:'idAcceso');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
            $table->string(column:'direccion_ip');
            $table->date(column:'fecha_acceso');
            $table->time(column:'hora_acceso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesos');
    }
};
