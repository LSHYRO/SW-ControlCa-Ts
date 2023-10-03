<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tipo_personal extends Model
{
    use HasFactory;
    protected $table = "tipo_personal";

    protected $fillable = [
        'tipo_personal',
    ];

    public function personal(): BelongsTo
    {
        return $this->belongsTo(personal::class, 'id_tipo_personal', 'id_tipo_personal');
    }
}
