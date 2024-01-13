<?php

namespace Database\Seeders;

use App\Models\generos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            "Hombre",
            "Mujer",
            "Otro",
        ];
        foreach ($generos as $genero) {
            generos::create(['genero' => $genero]);
        }
    }
}
