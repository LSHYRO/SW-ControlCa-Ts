<?php

namespace Database\Factories;

use App\Models\personas;
use App\Models\profesores;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\profesores>
 */
class profesoresFactory extends Factory
{
    protected $model = profesores::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tablaPersona = personas::inRandomOrder()->first();
        return [
            'correoElectronico' => $this->faker->email(),
            'numTelefono' => $this->faker->phoneNumber(),
            'activo' => $this->faker->boolean(),
            'idPersona' => $tablaPersona->idPersona
        ];
    }
}
