<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tiposActividades extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipoActividad',
    ];

    public function tiposActividades(): BelongsToMany
    {
        return $this->belongsToMany(tiposActividades::class, 'idTipoActividad', 'idTipoActividad');
    }
}
