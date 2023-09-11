<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class personas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'apellidoP',
        'apellidoM',
        'nombre',
        'fechaNacimiento',
        'idUsuario',
    ];

    public function usuarios(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }

    public function personal_escolar(): BelongsTo
    {
        return $this->belongsTo(personal_escolar::class, 'idPersona', 'idPersona');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(director::class, 'idPersona', 'idPersona');
    }

    public function tutores(): BelongsTo
    {
        return $this->belongsTo(tutores::class, 'idPersona', 'idPersona');
    }

    public function profesores(): BelongsTo
    {
        return $this->belongsTo(profesores::class, 'idPersona', 'idPersona');
    }

    public function alumnos(): BelongsTo
    {
        return $this->belongsTo(alumnos::class, 'idPersona', 'idPersona');
    }
}
