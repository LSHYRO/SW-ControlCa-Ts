<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class usuarios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "usuarios";

    protected $fillable = [
        'usuario',
        'contrasenia',
        'idTipoUsuario',
    ];
    
    protected $hidden = [
        'contrasenia'
    ];

    public function personas(): BelongsTo
    {
        return $this->belongsTo(personas::class, 'idUsuario', 'idUsuario');
    }

    public function usuarios_tiposUsuarios(): BelongsToMany{
        return $this->belongsToMany(tipoUsuarios::class,'idUsuario', 'idUsuario');
    }
}
