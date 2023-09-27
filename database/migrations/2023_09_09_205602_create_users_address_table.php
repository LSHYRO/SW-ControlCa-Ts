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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id(column:'idDireccion');
            $table->string(column:'calle');
            $table->string(column:'numero');    
            $table->string(column:'colonia');
            $table->string(column:'municipio');
            $table->string(column:'ciudad');
            $table->string(column:'estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
