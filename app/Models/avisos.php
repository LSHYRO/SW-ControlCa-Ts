<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class avisos extends Model
{
    use HasFactory;

    protected $table = "avisos";
    protected $primaryKey = 'idAviso';

    protected $fillable = [
        'idUsuario',
        'titulo',
        'descripcion',
        'lugar',
        'fechaRealizacion',
        'fechaHoraInicio',
        'fechaHoraFin',
    ];

    public function usuarios(): HasOne
    {
        return $this->hasOne(usuarios::class, 'idUsuario', 'idUsuario');
    }
}
