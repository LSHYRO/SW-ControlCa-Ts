<?php

use App\Models\municipios;
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
        Schema::create('asentamientos', function (Blueprint $table) {
            $table->id(column:'idAsentamiento');
            $table->string(column:'Asentamiento');
            $table->foreignId(column:'idMunicipio')->references('idMunicipio')->on('municipios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asentamientos');
    }
};
