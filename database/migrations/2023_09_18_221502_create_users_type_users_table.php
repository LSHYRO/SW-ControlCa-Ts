<?php

use App\Models\tipoUsuarios;
use App\Models\usuarios;
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
        Schema::create('usuarios_tiposUsuarios', function (Blueprint $table) {
            $table->id(column:'idUsuarios_Tipos');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios'); // id del usuario que crea el tipo de Usuario
            $table->foreignId(column:'idTipoUsuario')->references('idTipoUsuario')->on('tipoUsuarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_tiposUsuarios');
    }
};
