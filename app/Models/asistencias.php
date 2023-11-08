<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class asistencias extends Model
{
    use HasFactory;

    protected $primaryKey = 'idAsistencia';
    protected $table = 'asistencias';

    protected $fillable = [
        'asistencia',
        'fecha',
        'idAlumno',
        'idClase',
    ];

    public function alumno(): HasOne
    {
        return $this->hasOne(alumnos::class, 'idAlumno', 'idAlumno');
    }

    public function clase(): HasOne
    {
        return $this->hasOne(clases::class, 'idClase', 'idClase');
    }
}
