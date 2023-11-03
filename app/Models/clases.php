<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class clases extends Model
{
    use HasFactory;

    protected $table = "clases";
    protected $primaryKey = 'idClase';

    protected $fillable = [
        'idGrupo',
        'idGrado',
        'idProfesor',
        'idMateria',
        'idCiclo',
    ];

    public function grados(): HasOne
    {
        return $this->hasOne(grados::class, 'idGrado', 'idGrado');
    }

    public function grupos(): HasOne
    {
        return $this->hasOne(grupos::class, 'idGrupo', 'idGrupo');
    }

    public function profesor(): HasOne
    {
        return  $this->hasOne(profesores::class,'idProfesor','idProfesor');
    }

    public function materias(): HasOne
    {
        return $this->hasOne(materias::class, 'idMateria', 'idMateria');
    }

    public function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
    }

    public function clases_alumnos(): BelongsToMany
    {
        return $this->belongsToMany(clases_alumnos::class, 'idClase', 'idClase');
    }

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(actividades::class, 'idClase', 'idClase');
    }
}
