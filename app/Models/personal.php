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
        'CURP',
        'RFC',
        'correoElectronico',
        'numTelefono',
        'tipoSangre',
        'alergias',
        'discapacidad',
        'nombre_completo',
        'idDireccion',
        'id_tipo_personal',
        'idUsuario',
    ];

    public function usuarios(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }

    public function tipo_personal(): HasOne
    {
        return $this->hasOne(tipo_personal::class, 'id_tipo_personal', 'id_tipo_personal');
    }

    public function direcciones(): HasOne
    {
        return $this->hasOne(direcciones::class,'idDireccion', 'idDireccion');
    }
    
    protected function nombre(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }
    /*
    protected function activo(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value ? 'Si' : 'No', //Funcion flecha (Como en JavaScript), Laravel > 8
            set: fn($value) => $value
        );
    }
    */

    

}
