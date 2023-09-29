<?php

namespace Database\Factories;

use App\Models\tipoUsuarios;
use App\Models\usuarios;
use App\Models\usuarios_tiposUsuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class usuarios_tiposUsuariosFactory extends Factory
{
    protected $model = usuarios_tiposUsuarios::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $tablaUsuario = usuarios::inRandomOrder()->first();
        $tablatipoUsuarios = tipoUsuarios::inRandomOrder()->first();

        return [
            'idUsuario' => $tablaUsuario->idUsuario,
            'idTipoUsuario' => $tablatipoUsuarios->idTipoUsuario
        ];
    }
}
