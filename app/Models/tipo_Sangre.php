<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tipo_Sangre extends Model
{
    use HasFactory;
    protected $table = 'tipo_Sangre';  //referencia a la tabla de nuestra base de datos
    protected $primaryKey = 'idTipoSangre';
    protected $fillable = [
        'tipoSangre',
    ];

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idTipoSangre', 'idTipoSangre');
    }

    public function personal(): BelongsToMany
    {
        return $this->belongsToMany(personal::class, 'idTipoSangre', 'idTipoSangre');
    }
}
