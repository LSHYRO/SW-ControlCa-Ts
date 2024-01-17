<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class usuarios extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use SoftDeletes;

    protected $table = "usuarios";
    protected $primaryKey = 'idUsuario';
    protected $username = 'usuario';
    protected $password = 'password';

    protected $fillable = [
        'usuario',
        'contrasenia',
        'idTipoUsuario'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
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

    public function tipoUsuarios(): HasOne
    {
        return $this->hasOne(tipoUsuarios::class, 'idTipoUsuario', 'idTipoUsuario');
    }

    public function avisos(): BelongsToMany
    {
        return $this->belongsToMany(avisos::class, 'idUsuario', 'idUsuario');
    }

    public function accesos(): BelongsToMany
    {
        return $this->belongsToMany(accesos::class, 'idUsuario', 'idUsuario');
    }
    /*
    protected $casts = [
        'password' => 'hashed',
    ];
*/
    public function getAuthIdentifier()
    {
        return $this->attributes['idUsuario'];
    }
}
