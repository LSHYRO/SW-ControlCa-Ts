<?php

namespace Database\Factories;

use App\Models\materias;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class materiasFactory extends Factory
{
    protected $model = materias::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'materia' => $this->faker->sentence(),
            'descripcion' => $this->faker->paragraph(),
            'esTaller' => $this->faker->boolean()
        ];
    }
}
