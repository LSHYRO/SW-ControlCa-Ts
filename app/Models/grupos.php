<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class grupos extends Model
{
    use HasFactory;

    protected $fillable = [
        'grupo',
        'idCiclo',
    ];

    public function ciclos(): HasMany
    {
        return $this->hasMany(ciclos::class, 'idCiclo', 'idCiclo');
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idGrupo', 'idGrupo');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idGrupo', 'idGrupo');
    }
}
