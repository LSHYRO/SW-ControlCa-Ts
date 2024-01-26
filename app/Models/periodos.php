<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class periodos extends Model
{
    use HasFactory;

    protected $table = "periodos";
    protected $primaryKey = 'idPeriodo';

    protected $fillable = [
        'periodo', 
        'fecha_inicio',
        'fecha_fin',
        'idCiclo',
    ];

    protected function ciclos(): HasOne
    {
        return $this->hasOne(ciclos::class, 'idCiclo', 'idCiclo');
    }

    protected function actividades(): BelongsToMany
    {
        return $this->belongsToMany(actividades::class, 'idPeriodo', 'idPeriodo');
    }

    protected function calificiones_periodos(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones_periodos::class, 'idPeriodo', 'idPeriodo');
    }
}
