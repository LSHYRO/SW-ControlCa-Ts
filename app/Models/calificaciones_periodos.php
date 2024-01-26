<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class calificaciones_periodos extends Model
{
    use HasFactory;

    protected $table = "calificaciones_periodos";
    protected $primaryKey = 'idCalificacionPeriodo';

    protected $fillable = [
        'idClase',
        'idAlumno',
        'idPeriodo',
        'calificacion',
    ];

    public function clases(): HasOne{
        return $this->hasOne(clases::class, 'idClase', 'idClase');
    }

    public function alumnos(): HasOne{
        return $this->hasOne(alumnos::class, 'idAlumno', 'idAlumno');
    }

    public function periodos(): HasOne{
        return $this->hasOne(periodos::class, 'idPeriodo', 'idPeriodo');
    }
}
