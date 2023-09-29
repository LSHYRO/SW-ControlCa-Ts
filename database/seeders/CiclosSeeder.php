<?php

namespace Database\Seeders;

use App\Models\ciclos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CiclosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciclo1 = new ciclos();
        $ciclo1->fecha_inicio = '2022-07-15';
        $ciclo1->fecha_fin = '2023-06-14';
        $ciclo1->descripcionCiclo = $ciclo1->fecha_inicio + " - " + $ciclo1->fecha_fin;
        $ciclo1->save();
    }
}
