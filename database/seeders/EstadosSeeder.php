<?php

namespace Database\Seeders;

use App\Models\estados;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            "Aguascalientes",
            "Baja California",
            "Baja California Sur",
            "Campeche",
            "Coahuila",
            "Colima",
            "Chiapas",
            "Chihuahua",
            "Durango",
            "Distrito Federal",
            "Guanajuato",
            "Guerrero",
            "Hidalgo",
            "Jalisco",
            "México",
            "Michoacán",
            "Morelos",
            "Nayarit",
            "Nuevo León",
            "Oaxaca",
            "Puebla",
            "Querétaro",
            "Quintana Roo",
            "San Luis Potosí",
            "Sinaloa",
            "Sonora",
            "Tabasco",
            "Tamaulipas",
            "Tlaxcala",
            "Veracruz",
            "Yucatán",
            "Zacatecas",
        ];

        foreach ($estados as $estado) {
            estados::create(['estado' => $estado]);
        }
    }
}
