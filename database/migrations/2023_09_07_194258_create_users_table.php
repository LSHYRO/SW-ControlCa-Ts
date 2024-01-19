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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(column:'idUsuario');
            $table->string('usuario')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('contrasenia')->nullable(false);
            $table->int('intentos')->nullable(false)->default(10);
            $table->foreignId(column:'idTipoUsuario')->references('idTipoUsuario')->on('tipoUsuarios');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
