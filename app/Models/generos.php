<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class generos extends Model
{
    use HasFactory;
    protected $table = "generos";
    protected $primaryKey = "idGenero";

    protected $fillable = [
        'genero'
    ];

    public function personal(): BelongsToMany
    {
        return $this->belongsToMany(personal::class, 'idGenero', 'idGenero');
    }

    public function tutores(): BelongsToMany
    {
        return $this->belongsToMany(tutores::class, 'idGenero', 'idGenero');
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(alumnos::class, 'idGenero', 'idGenero');
    }
}
