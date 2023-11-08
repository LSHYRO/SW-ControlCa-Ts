<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class estados extends Model
{
    use HasFactory;

    protected $table = "estados";
    protected $primaryKey = 'idEstado';

    protected $fillable = [
        'estado',
    ];

    public function direcciones(): BelongsToMany
    {
        return $this->belongsToMany(direcciones::class, 'idEstado', 'idEstado');
    }

    public function codigoPostal(): BelongsToMany
    {
        return $this->belongsToMany(codigoPostal::class, 'idEstado', 'idEstado');
    }
}