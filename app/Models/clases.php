<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class clases extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function personal(): HasOne
    {
        return  $this->hasOne(personal::class,'idPersonal','idPersonal');
    }

    public function materias(): HasOne
    {
        return $this->hasOne(materias::class, 'idMateria', 'idMateria');
    }

    public function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
    }

    public function clases_alumnos(): BelongsTo
    {
        return $this->belongsTo(clases_alumnos::class, 'idClase', 'idClase');
    }

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(actividades::class, 'idClase', 'idClase');
    }

    public function asistencias(): BelongsToMany
    {
        return $this->belongsToMany(asistencias::class, 'idClase', 'idClase');
    }

    public function calificaciones(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones::class, 'idClase', 'idClase');
    }

    public function calificaciones_periodos(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones_periodos::class, 'idClase', 'idClase');
    }
}
