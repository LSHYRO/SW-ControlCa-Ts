<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class alumnos extends Model
{
    use HasFactory;

    protected $table = "alumnos";
    protected $primaryKey = 'idAlumno';

    protected $fillable = [
        'apellidoP',
        'apellidoM',
        'nombre',
        'fechaNacimiento',
        'CURP',
        'idGenero',
        'correoElectronico',
        'numTelefono',
        'idTipoSangre',
        'alergias',
        'discapacidad',
        'idDireccion',
        'esForaneo',
        /*
        'idGrado',
        'idGrupo',
        */
        'idMateria',
        'idTutor',
        'idUsuario',
        'nombre_completo',
    ];

    protected function nombre(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }

    protected function apellidoP(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }

    protected function apellidoM(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }

    protected function CURP(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtoupper($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtoupper($value);
            }
        );
    }

    public function clases(): HasMany
    {
    return $this->hasMany(clases::class, 'idAlumno');
    }

    public function grados(): HasOne
    {
        return $this->hasOne(grados::class, 'idGrado', 'idGrado');
    }

    public function grupos(): HasOne
    {
        return $this->hasOne(grupos::class, 'idGrupo', 'idGrupo');
    }

    public function usuarios(): HasOne
    {
        return $this->hasOne(alumnos::class, 'idUsuario', 'idUsuario');
    }

    public function materias(): HasOne
    {
        return $this->hasOne(materias::class, 'idMateria', 'idMateria');
    }

    public function tutores(): BelongsTo
    {
        return $this->belongsTo(tutores::class, 'idTutor', 'idTutor');
    }

    public function clases_alumnos(): BelongsTo
    {
        return $this->belongsTo(clases_alumnos::class, 'idAlumno', 'idAlumno');
    }

    public function calificaciones(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones::class, 'idAlumno', 'idAlumno');
    }

    public function direcciones(): HasOne
    {
        return $this->hasOne(direcciones::class,'idDireccion', 'idDireccion');
    }

    public function asistencias(): BelongsToMany
    {
        return $this->belongsToMany(asistencias::class, 'idAlumno', 'idAlumno');
    }

    public function tipo_Sangre(): HasOne 
    {
        return $this->hasOne(tipo_Sangre::class, 'idTipoSangre','idTipoSangre');
    }

    public function generos(): HasOne
    {
        return $this->hasOne(generos::class, 'idGenero', 'idGenero');
    }

    public function calificaciones_periodos(): BelongsToMany 
    {
        return $this->belongsToMany(calificaciones_periodos::class, 'idAlumnos', 'idAlumnos');
    }

    public function clasesInscritas()
    {
        return $this->belongsToMany(clases::class, 'clases_alumnos', 'idAlumno', 'idClase');
    }

    public function grado_grupo_alumno(): BelongsToMany
    {
        return $this->belongsToMany(grado_grupo_alumno::class, 'idAlumno', 'idAlumno');
    }
}
