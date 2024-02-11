<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class tutores extends Model
{
    use HasFactory;

    protected $table = "tutores";
    protected $primaryKey = 'idTutor';

    protected $fillable = [
        'apellidoP',
        'apellidoM',
        'nombre',
        'numTelefono',
        'correoElectronico',
        'idGenero',
        'idDireccion',
        'idUsuario',
        'nombre_completo',
    ];

    public function usuarios():HasOne
    {
        return $this->hasOne(tutores::class, 'idUsuario', 'idUsuario');
    }

    public function alumnos(): HasMany{
        return $this->hasMany(alumnos::class, 'idTutor', 'idTutor');
    }

    public function direcciones(): HasOne
    {
        return $this->hasOne(direcciones::class,'idDireccion', 'idDireccion');
    }

    public function generos(): HasOne
    {
        return $this->hasOne(generos::class, 'idGenero', 'idGenero');
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
    
    protected function apellidoP(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return ucwords(strtolower($value));
            }
        );
    }

    protected function apellidoM(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return ucwords(strtolower($value));
            }
        );
    }

    
}
