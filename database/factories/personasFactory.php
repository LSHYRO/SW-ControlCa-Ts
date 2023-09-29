<?php

namespace Database\Factories;

use App\Models\personas;
use App\Models\usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\personas>
 */
class personasFactory extends Factory
{
    protected $model = personas::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tablaUsuario = usuarios::inRandomOrder()->first();
        return [
            'apellidoP' => $this->faker->lastName(),
            'apellidoM' => $this->faker->lastName(),
            'nombre' => $this->faker->name(),
            'fechaNacimiento'=> $this->faker->date($format='Y-m-d', $max='now'),
            'idUsuario' => $tablaUsuario->idUsuario
        ];
    }
}
