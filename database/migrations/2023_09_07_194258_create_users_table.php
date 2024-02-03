<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->string('usuario')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('contrasenia')->nullable(false);
            $table->integer('intentos')->nullable(false)->default(10);
            $table->dateTime('fecha_Creacion')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('cambioContrasenia')->nullable(false)->default(false);
            $table->foreignId('idTipoUsuario')->references('idTipoUsuario')->on('tipoUsuarios');
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
