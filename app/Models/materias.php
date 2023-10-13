<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class materias extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "materias";
    protected $primaryKey = 'idMateria';

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
    
    protected function extracurricular(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value ? 'Si' : 'No', //Funcion flecha (Como en JavaScript), Laravel > 8
            set: fn($value) => (bool) $value
        );
    }
}
 