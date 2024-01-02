<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'esTaller',
    ];

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idMateria', 'idMateria');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idMateria', 'idMateria');
    }

    /*
    
    protected function esTaller(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value ? 'Si' : 'No', //Funcion flecha (Como en JavaScript), Laravel > 8
            set: fn($value) => (boolean) $value
        );
    }
    */
}
 