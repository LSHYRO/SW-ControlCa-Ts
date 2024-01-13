<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class direcciones extends Model
{
    use HasFactory;
    
    protected $table = "direcciones";
    protected $primaryKey = 'idDireccion';

    protected $fillable = [
        'calle',
        'numero',
        'idAsentamiento',
    ];

    public function tutores(): BelongsToMany
    {
        return $this->belongsToMany(tutores::class, 'idDireccion', 'idDireccion');
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idDireccion', 'idDireccion');
    }

    public function personal(): BelongsToMany
    {
        return $this->belongsToMany(personal::class, 'idDireccion', 'idDireccion');
    }

    public function asentamientos(): HasOne
    {
        return $this->hasOne(asentamientos::class, 'idAsentamiento', 'idAsentamiento');
    }
    /*
    public function estados(): HasOne
    {
        return $this->hasOne(estados::class, 'idEstado', 'idEstado');
    }
    */
    protected function calle(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }
}
