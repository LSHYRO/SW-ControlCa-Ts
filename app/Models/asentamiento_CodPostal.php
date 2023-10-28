<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class asentamiento_CodPostal extends Model
{
    use HasFactory;

    protected $table = 'asentamiento_codPostal';
    protected $primaryKey = 'idAsentamiento_CodP';

    protected $fillable = [
        'idCodigoPostal',
        'idAsentamiento',
    ];

    public function codigoPostal(): HasOne
    {
        return $this->hasOne(codigoPostal::class, 'idCodigoPostal', 'idCodigoPostal');
    }

    public function asentamientos(): HasOne
    {
        return $this->hasOne(asentamientos::class, 'idAsentamiento', 'idAsentamiento');
    }
}
