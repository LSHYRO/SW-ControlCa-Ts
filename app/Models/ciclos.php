<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ciclos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ciclos";

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'descripcionCiclo',
        'activo',
    ];

    public function periodos(): BelongsTo
    {
        return $this->belongsTo(periodos::class, 'idCiclo', 'idCiclo');
    }

    public function grupos(): BelongsTo
    {
        return $this->belongsTo(grupos::class, 'idCiclo', 'idCiclo');
    }

    public function grados(): BelongsTo
    {
        return $this->belongsTo(grados::class, 'idCiclo', 'idCiclo');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(ciclos::class, 'idCiclo', 'idCiclo');
    }
}
