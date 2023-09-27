<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class director extends Model
{
    use HasFactory;

    protected $table = "director";

    protected $fillable = [
        'correoElectronico',
        'numTelefono',
        'idPersona',
        'activo',
    ];

    public function personas(): HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }
}
