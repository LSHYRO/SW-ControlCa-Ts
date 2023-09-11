<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class calificaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'idActividad',
        'idAlumno',
        'calificacion',
    ];

    public function actividades(): HasOne
    {
        return $this->hasOne(actividades::class, 'idActividad', 'idActividad');
    }

    public function alumnos(): HasOne
    {
        return 	$this->hasOne(alumnos::class,'idAlumno','idAlumno');
    }
}
