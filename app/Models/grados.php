<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class grados extends Model
{
    use HasFactory;

    protected $fillable = [
        'grado',
        'idCiclo',
    ];

    public function ciclos(): HasMany
    {
        return $this->hasMany(ciclos::class, 'idCiclo', 'idCiclo');
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idGrado', 'idGrado');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idGrado', 'idGrado');
    }
}
