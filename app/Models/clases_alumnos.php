<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class clases_alumnos extends Model
{
    use HasFactory;

    protected $table = "clases_alumnos";
    protected $primaryKey = 'idClaseAlumno';

    protected $fillable = [
        'idClase',
        'idAlumno',
    ];

    public function clases(): HasOne
    {
        return $this->hasOne(clases::class, "idClase", "idClase");
    }

    public function alumnos(): HasOne
    {
        return $this->hasOne(alumnos::class, 'idAlumno', 'idAlumno');
    }
}
