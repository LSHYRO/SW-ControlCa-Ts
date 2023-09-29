<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class estados extends Model
{
    use HasFactory;

    protected $table = "estados";

    protected $fillable = [
        'estado',
    ];

    public function direcciones(): BelongsTo
    {
        return $this->belongsTo(direcciones::class, 'idEstado', 'idEstado');
    }
}
