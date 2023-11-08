<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class asentamientos extends Model
{
    use HasFactory;

    protected $table = 'asentamientos';
    protected $primaryKey = 'idAsentamiento';

    protected $fillable = [
        'asentamiento',
        'idMunicipio',
        'idCodigoPostal'
    ];

    public function municipios(): HasOne
    {
        return $this->hasOne(municipios::class, 'idMunicipio', 'idMunicipio');
    }

    public function codPostal(): HasOne
    {
        return $this->hasOne(codigoPostal::class, 'idCodigoPostal', 'idCodigoPostal');
    }

    public function direcciones(): BelongsToMany
    {
        return $this->belongsToMany(direcciones::class, 'idAsentamiento', 'idAsentamiento');
    }

    public function asentamiento_CodPostal(): BelongsToMany
    {
        return $this->belongsToMany(asentamiento_CodPostal::class, 'idAsentamiento', 'idAsentamiento');
    }
}
