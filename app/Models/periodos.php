<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class periodos extends Model
{
    use HasFactory;

    protected $table = "periodos";
    protected $primaryKey = 'idPeriodo';

    protected $fillable = [
        'periodo', 
        'fecha_inicio',
        'fecha_fin',
        'act',
        'activo',
        'idCiclo',
    ];

    protected function ciclos(): HasMany
    {
        return $this->hasMany(ciclos::class, 'idCiclo', 'idCiclo');
    }

    protected function actividades(): BelongsToMany
    {
        return $this->belongsToMany(actividades::class, 'idPeriodo', 'idPeriodo');
    }
}
