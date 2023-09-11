<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class alumnos extends Model
{
    use HasFactory;

    protected $fillable = [
        'CURP',
        'estatus',
        'idGrado',
        'idGrupo',
        'idPersona',
        'idMateria',
        'idTutor',
    ];

    public function grados(): HasOne
    {
        return $this->hasOne(grados::class, 'idGrado', 'idGrado');
    }

    public function grupos(): HasOne
    {
        return $this->hasOne(grupos::class, 'idGrupo', 'idGrupo');
    }

    public function personas(): HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }

    public function materias(): HasOne
    {
        return $this->hasOne(materias::class, 'idMateria', 'idMateria');
    }

    public function tutores(): HasOne
    {
        return $this->hasOne(tutores::class, 'idTutor', 'idTutor');
    }

    public function clases_alumnos(): BelongsToMany
    {
        return $this->belongsToMany(clases_alumnos::class, 'idAlumno', 'idAlumno');
    }

    public function calificaciones(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones::class, 'idAlumno', 'idAlumno');
    }
}
