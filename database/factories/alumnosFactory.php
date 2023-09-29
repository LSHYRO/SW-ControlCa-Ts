<?php

namespace Database\Factories;

use App\Models\alumnos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\alumnos>
 */
class alumnosFactory extends Factory
{
    protected $model = alumnos::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'CURP' => $this->faker->sentence(18),
            'estatus' => $this->faker->boolean(),
            
        ];
    }
}
