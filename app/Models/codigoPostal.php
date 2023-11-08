<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class codigoPostal extends Model
{
    use HasFactory;

    protected $table = "codigoPostal";
    protected $primaryKey = 'idCodigoPostal';

    protected $fillable = [
        'codigoPostal',
        'idEstado',
        'idMunicipio'
    ];

    public function estados(): HasOne
    {
        return $this->hasOne(estados::class, 'idEstado', 'idEstado');
    }

    public function asentamiento_CodPostal(): BelongsToMany
    {
        return $this->belongsToMany(asentamiento_CodPostal::class, 'idCodigoPostal', 'idCodigoPostal');
    }

    public function municipios(): HasOne
    {
        return $this->hasOne(municipios::class, 'idMunicipio', 'idMunicipio');
    }

    public function asentamientos(): BelongsToMany
    {
        return $this->belongsToMany(asentamientos::class, 'idCodigoPostal', 'idCodigoPostal');
    }
}
