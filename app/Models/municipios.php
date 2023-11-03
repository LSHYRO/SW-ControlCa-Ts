<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Casts\Attribute;

class municipios extends Model
{
    use HasFactory;

    protected $table = "municipios";
    protected $primaryKey = "idMunicipio";

    protected $fillable = [
        'municipio',
        'idEstado',
    ];

    public function estados(): HasOne
    {
        return $this->hasOne(estados::class, 'idEstado', 'idEstado');
    }

    public function codPostal(): BelongsToMany
    {
        return $this->belongsToMany(codigoPostal::class, 'idMunicipio', 'idMunicipio');
    }

    protected function municipio(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value), //Funcion flecha (Como en JavaScript), Laravel > 8
            set: function($value){
                return strtolower($value);
            }
        );
    }
}
