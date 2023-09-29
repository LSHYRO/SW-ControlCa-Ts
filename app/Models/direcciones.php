<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class direcciones extends Model
{
    use HasFactory;
    
    protected $table = "direcciones";

    protected $fillable = [
        'calle',
        'numero',
        'colonia',
        'municipio',
        'ciudad',
        'idEstado',
    ];

    public function tutores(): BelongsToMany
    {
        return $this->belongsToMany(tutores::class, 'idDireccion', 'idDireccion');
    }

    public function estados(): HasMany
    {
        return $this->hasMany(estados::class, 'idEstado', 'idEstado');
    }
}
