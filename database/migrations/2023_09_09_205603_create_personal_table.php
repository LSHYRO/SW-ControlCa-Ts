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
            $table->foreignId(column:'idGenero')->references('idGenero')->on('generos');
            $table->string(column:'CURP')->nullable(false);
            $table->string(column:'RFC')->nullable(false);
            $table->string(column:'correoElectronico')->nullable(false);
            $table->string(column:'numTelefono')->nullable(false);
            $table->foreignId(column:'idTipoSangre')->references('idTipoSangre')->on('tipo_Sangre');
            $table->string(column:'alergias')->nullable(true);
            $table->string(column:'discapacidad')->nullable(true);
            $table->text('nombre_completo')->nullable()->fulltext();            
            $table->foreignId(column: 'idDireccion')->references('idDireccion')->on('direcciones');
            $table->foreignId(column:'id_tipo_personal')->references('id_tipo_personal')->on('tipo_personal');
            $table->foreignId(column:'idUsuario')->references('idUsuario')->on('usuarios');
            $table->timestamps();            
            $table->softDeletes();
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
