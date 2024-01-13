<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class asentamientos extends Model
{
    use HasFactory;

    protected $table = 'asentamientos';
    protected $primaryKey = 'idAsentamiento';

    protected $fillable = [
        'asentamiento',
        'tipo',
        'idMunicipio',
        'idCodigoPostal'
    ];

    public function municipios(): BelongsTo
    {
        return $this->belongsTo(municipios::class, 'idMunicipio', 'idMunicipio');
    }

    public function codigoPostal(): BelongsTo
    {
        return $this->belongsTo(codigoPostal::class, 'idCodigoPostal', 'idCodigoPostal');
    }

    public function direcciones(): BelongsToMany
    {
        return $this->belongsToMany(direcciones::class, 'idAsentamiento', 'idAsentamiento');
    }
    

}
