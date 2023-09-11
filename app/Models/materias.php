<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class materias extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'materia',
        'descripcion',
        'extracurricular',
        'activo',
    ];

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumno::class, 'idMateria', 'idMateria');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idMateria', 'idMateria');
    }
}
 