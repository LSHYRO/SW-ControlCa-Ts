<?php

namespace Database\Factories;

use App\Models\usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class usuariosFactory extends Factory
{
    protected $model = usuarios::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario' => $this->faker->userName(), 
            'contrasenia' => $this->faker->password(4,8), 
            'activo' => $this->faker->boolean(), 
            'remember_token' => $this->faker->sentence(7,true),
        ];
    }
}
