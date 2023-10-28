<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class accesos extends Model
{
    use HasFactory;

    protected $table = "accesos";
    protected $primaryKey = 'idAcceso';

    protected $fillable = [
        'idUsuario',
        'direccion_ip',
        'fecha_acceso',
        'hora_acceso',
    ];

    public function usuarios(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }
}
