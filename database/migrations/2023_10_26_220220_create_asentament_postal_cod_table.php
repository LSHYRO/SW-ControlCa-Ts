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
        Schema::create('asentamiento_codPostal', function (Blueprint $table) {
            $table->id(column:'idAsentamiento_CodP');
            $table->foreignId(column:'idCodigoPostal')->references('idCodigoPostal')->on('codigoPostal');
            $table->foreignId(column:'idAsentamiento')->references('idAsentamiento')->on('asentamientos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asentamiento_codPostal');
    }
};
