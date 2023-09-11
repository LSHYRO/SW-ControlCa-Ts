<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class usuarios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'usuario',
        'contrasenia',
        'idTipoUsuario',
    ];
    
    protected $hidden = [
        'contrasenia'
    ];

    public function tipoUsuarios(): HasOne
    {
        return $this->hasOne(tipoUsuarios::class, 'idTipoUsuario', 'idTipoUsuario');
    }

    public function personas(): BelongsTo
    {
        return $this->belongsTo(personas::class, 'idUsuario', 'idUsuario');
    }
}
