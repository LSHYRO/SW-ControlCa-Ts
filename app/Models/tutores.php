<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class tutores extends Model
{
    use HasFactory;

    protected $table = "tutores";

    protected $fillable = [
        'numTelefono',
        'idDireccion',
        'idPersona',
        'activo',
    ];

    public function personas():HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }

    public function alumnos(): BelongsToMany{
        return $this->belongsToMany(alumnos::class, 'idTutor', 'idTutor');
    }

    public function direcciones(): HasOne
    {
        return $this->hasOne(direcciones::class,'idDireccion', 'idDireccion');
    }

}
