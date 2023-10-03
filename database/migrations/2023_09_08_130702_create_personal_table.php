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
        Schema::create('personal', function (Blueprint $table) {
            $table->id(column:'idPersonal');
            $table->string(column:'apellidoP')->nullable(false);
            $table->string(column:'apellidoM')->nullable(false);
            $table->string(column:'nombre')->nullable(false);
            $table->date(column:'fechaNacimiento')->nullable(false);
            $table->string(column:'correoElectronico')->unique()->nullable(false);
            $table->string(column:'numTelefono')->nullable(false);
            $table->boolean('activo');
            $table->text('nombre_completo')->nullable()->fulltext();
            $table->timestamps();            
            $table->softDeletes();
            $table->foreignId(column:'id_tipo_personal')->references('id_tipo_personal')->on('tipo_personal');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
