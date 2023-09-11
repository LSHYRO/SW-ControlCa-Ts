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
        Schema::create('personal_escolar', function (Blueprint $table) {
            $table->id(column:'idPersonal');
            $table->string(column:'correoElectronico')->unique()->nullable(false);
            $table->string(column:'numTelefono')->nullable(false);
            $table->foreignId(column:'idPersona')->references('idPersona')->on('personas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_escolar');
    }
};
