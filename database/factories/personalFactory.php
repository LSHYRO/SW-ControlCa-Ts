<?php

namespace Database\Factories;

use App\Models\personal;
use App\Models\tipo_personal;
use App\Models\usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\personas>
 */
class personalFactory extends Factory
{
    protected $model = personal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tablaUsuario = usuarios::inRandomOrder()->first();
        $tablaTipoPersonal = tipo_personal::inRandomOrder()->first();   
        $nombre = $this->faker->name();
        $apellidoP = $this->faker->lastName();
        $apellidoM = $this->faker->lastName();

        return [
            'apellidoP' => $apellidoP,
            'apellidoM' => $apellidoM,
            'nombre' => $nombre,
            'fechaNacimiento'=> $this->faker->date($format='Y-m-d', $max='now'),
            'correoElectronico' => $this->faker->email(),
            'numTelefono' => $this->faker->phoneNumber(),
            'activo' => $this->faker->boolean(),
            'nombre_completo' => $nombre . ' ' . $apellidoP . ' ' . $apellidoM,
            'id_tipo_personal' => $tablaTipoPersonal->id_tipo_personal,
            'idUsuario' => $tablaUsuario->idUsuario
        ];
    }
}
