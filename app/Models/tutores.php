<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class tutores extends Model
{
    use HasFactory;

    protected $table = "tutores";

    protected $fillable = [
        'numTelefono',
        'direccion',
        'idPersona',
    ];

    public function personas():HasOne
    {
        return $this->hasOne(personas::class, 'idPersona', 'idPersona');
    }

    public function alumnos(): BelongsToMany{
        return $this->belongsToMany(alumnos::class, 'idTutor', 'idTutor');
    }

}
