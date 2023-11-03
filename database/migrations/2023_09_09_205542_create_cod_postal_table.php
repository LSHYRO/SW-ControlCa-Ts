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
        Schema::create('codigoPostal', function (Blueprint $table) {
            $table->id(column:'idCodigoPostal');
            $table->integer(column:'codigoPostal');
            $table->foreignId(column:'idEstado')->references('idEstado')->on('estados');
            $table->foreignId(column:'idMunicipio')->references('idMunicipio')->on('municipios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigoPostal');
    }
};
