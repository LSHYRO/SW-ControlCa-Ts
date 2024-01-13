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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id(column:'idPeriodo');
            $table->string(column:'periodo')->nullable(false);
            $table->date(column:'fecha_inicio')->nullable(false);
            $table->date(column:'fecha_fin')->nullable(false);
            $table->softDeletes();
            $table->foreignId(column:'idCiclo')->references('idCiclo')->on('ciclos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
