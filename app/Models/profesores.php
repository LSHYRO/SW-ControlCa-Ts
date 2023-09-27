<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class profesores extends Model
{
    use HasFactory;

    protected $table = "profesores";

    protected $fillable = [
        'correoElectronico',
        'numTelefono', 
        'idPersona',
        'activo',
    ];

    public function personas(): HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(clases::class, 'idProfesor', 'idProfesor');
    }
}
