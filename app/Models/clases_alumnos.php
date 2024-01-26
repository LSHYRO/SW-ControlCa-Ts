<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class clases_alumnos extends Model
{
    use HasFactory;

    protected $table = "clases_alumnos";
    protected $primaryKey = 'idClaseAlumno';

    protected $fillable = [
        'idClase',
        'idAlumno',
        'calificacionClase'
    ];

    public function clases(): HasMany
    {
        return $this->hasMany(clases::class, "idClase", "idClase");
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(alumnos::class, 'idAlumno', 'idAlumno');
    }
}
