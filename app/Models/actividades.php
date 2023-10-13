<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class actividades extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "actividades";
    protected $primaryKey = 'idActividad';

    protected $fillable = [
        'descripcion',
        'idClase',
        'idPeriodo',
        'idTipoActividad',
    ];

    public function clases(): HasOne
    {
        return $this->hasOne(clases::class, 'idClase', 'idClase');
    }

    public function periodos(): HasOne
    {
        return $this->hasOne(periodos::class, 'idPeriodo', 'idPeriodo');
    }

    public function tiposActividades(): HasOne
    {
        return $this->hasOne(tiposActividades::class, 'idTipoActividad', 'idTipoActividad');
    }

    public function calificaciones(): BelongsToMany
    {
        return $this->belongsToMany(calificaciones::class, 'idActividad', 'idActividad');
    }
}
