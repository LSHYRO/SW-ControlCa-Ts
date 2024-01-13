<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tipoUsuarios extends Model
{
    use HasFactory;

    protected $table = "tipoUsuarios";
    protected $primaryKey = 'idTipoUsuario';

    protected $fillable = [
        'tipoUsuario',
    ];

    public function usuarios_tiposUsuarios(): BelongsToMany
    {
        return $this->belongsToMany(usuarios_tiposUsuarios::class, 'idTipoUsuario', 'idTipoUsuario');
    }

    
}
