<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ciclos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ciclos";
    protected $primaryKey = 'idCiclo';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'descripcionCiclo',
        'activo',
    ];

    public function periodos(): BelongsToMany
    {
        return $this->belongsToMany(periodos::class, 'idCiclo', 'idCiclo');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(grupos::class, 'idCiclo', 'idCiclo');
    }

    public function grados(): BelongsToMany
    {
        return $this->belongsToMany(grados::class, 'idCiclo', 'idCiclo');
    }

    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(ciclos::class, 'idCiclo', 'idCiclo');
    }
    /*
    protected function fechaInicio(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return Carbon::parse($value)->format('d-m-Y');
            }
        );
    }
    */
}
