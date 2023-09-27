<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'estado',
    ];

    public function tutores(): BelongsToMany
    {
        return $this->belongsToMany(tutores::class, 'idDireccion', 'idDireccion');
    }
}
