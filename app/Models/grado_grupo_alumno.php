<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class grado_grupo_alumno extends Model
{
    use HasFactory;

    protected $table = "grado_grupo_alumno";
    protected $primaryKey = 'idGradGrupAl';

    protected $fillable = [        
        'idAlumno',
        'idGrado',
        'idGrupo',
        'idCiclo',
        'calificacion',
        'estatus'
    ];

    public function alumnos(): HasOne
    {
        return $this->hasOne(alumnos::class, 'idAlumno', 'idAlumno');
    }

    public function grados(): HasOne
    {
        return $this->hasOne(grados::class, 'idGrado', 'idGrado');
    }

    public function grupos(): HasOne
    {
        return $this->hasOne(grupos::class, 'idGrupo', 'idGrupo');
    }

    public function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
    }
}
