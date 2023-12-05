<?php

use App\Models\tipo_personal;
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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id(column: 'idAlumno');
            $table->string(column: 'apellidoP')->nullable(false);
            $table->string(column: 'apellidoM')->nullable(false);
            $table->string(column: 'nombre')->nullable(false);
            $table->date(column:'fechaNacimiento')->nullable(false);
            $table->string(column:'CURP')->nullable(false);
            $table->foreignId(column:'idGenero')->references('idGenero')->on('generos');
            $table->string(column:'correoElectronico')->nullable(false);
            $table->string(column:'numTelefono')->nullable(false);
            $table->foreignId(column:'idTipoSangre')->references('idTipoSangre')->on('tipo_Sangre');
            $table->string(column:'alergias')->nullable(false);
            $table->string(column:'discapacidad')->nullable(false);
            $table->foreignId(column: 'idDireccion')->references('idDireccion')->on('direcciones');
            $table->boolean(column:'esForaneo');
            $table->foreignId(column: 'idGrado')->references('idGrado')->on('grados');
            $table->foreignId(column: 'idGrupo')->references('idGrupo')->on('grupos');
            $table->foreignId(column: 'idMateria')->references('idMateria')->on('materias')->nullable(true);
            $table->foreignId(column: 'idTutor')->references('idTutor')->on('tutores');
            $table->foreignId(column: 'idUsuario')->references('idUsuario')->on('usuarios');
            $table->text('nombre_completo')->nullable()->fulltext();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
