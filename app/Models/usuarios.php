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
    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'usuario',
        'contrasenia',
        'idTipoUsuario',
        'activo',
    ];
    
    protected $hidden = [
        'contrasenia'
    ];

    public function alumnos(): BelongsTo
    {
        return $this->belongsTo(alumnos::class, 'idUsuario', 'idUsuario');
    }

    public function tutores(): BelongsTo
    {
        return $this->belongsTo(tutores::class, 'idUsuario', 'idUsuario');
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(personal::class, 'idUsuario', 'idUsuario');
    }

    public function usuarios_tiposUsuarios(): BelongsToMany{
        return $this->belongsToMany(tipoUsuarios::class,'idUsuario', 'idUsuario');
    }
}
