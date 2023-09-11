<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tipoUsuarios extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipoUsuario',
    ];

    public function Usuarios(): BelongsToMany
    {
        return $this->belongsToMany(usuarios::class, 'idTipoUsuario', 'idTipoUsuario');
    }
}
