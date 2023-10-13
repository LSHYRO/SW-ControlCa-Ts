<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;

class personal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "personal";
    protected $primaryKey = 'idPersonal';

    protected $fillable = [
        'apellidoP',
        'apellidoM',
        'nombre',
        'fechaNacimiento',
        'correoElectronico',
        'numTelefono',
        'activo',
        'nombre_completo',
        'id_tipo_personal',
        'idUsuario',
        'id_tipo_personal',
    ];

    public function usuarios(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }

    public function tipo_personal(): HasOne
    {
        return $this->hasOne(tipo_personal::class, 'id_tipo_personal', 'id_tipo_personal');
    }
    /*
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
    */
    protected function nombre(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }
    protected function activo(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value ? 'Si' : 'No', //Funcion flecha (Como en JavaScript), Laravel > 8
            set: fn($value) => $value
        );
    }
}
