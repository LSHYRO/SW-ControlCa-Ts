<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class grupos extends Model
{
    use HasFactory;

    protected $table = "grupos";
    protected $primaryKey = 'idGrupo';

    protected $fillable = [
        'grupo',
        'idCiclo',
    ];

    public function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
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
