<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class grados extends Model
{
    use HasFactory;

    protected $table = "grados";
    protected $primaryKey = 'idGrado';

    protected $fillable = [
        'grado',
        /*
        'idCiclo',
        */
    ];
    /*
    public function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
    }
    */
    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idGrado', 'idGrado');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idGrado', 'idGrado');
    }

    public function grado_grupo_alumno(): BelongsToMany
    {
        return $this->belongsToMany(grado_grupo_alumno::class, 'idGrado', 'idGrado');
    }
}
