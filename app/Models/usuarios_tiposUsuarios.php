<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class usuarios_tiposUsuarios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'usuarios_tiposUsuarios';
    protected $primaryKey = 'idusuarios_Tipos';

    protected $fillable = [
        'idUsuario',
        'idTipoUsuario',
    ];

    public function tipoUsuarios(): HasOne
    {
        return $this->hasOne(tipoUsuarios::class, "idTipoUsuario", "idTipoUsuario");
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }
}
