<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class personal_escolar extends Model
{
    use HasFactory;

    protected $table = "personal_escolar";

    protected $fillable = [
        'correoElectronico',
        'numTelefono',
        'idPersona',
    ];

    public function personas(): HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }
}
