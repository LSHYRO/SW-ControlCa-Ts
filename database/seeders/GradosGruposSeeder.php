<?php

namespace Database\Seeders;

use App\Models\grados;
use App\Models\grupos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradosGruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grados = [
            1,
            2,
            3,
        ];
        foreach ($grados as $grado) {
            grados::create(['grado' => $grado]);
        }

        grupos::create(['grupo' => 'A']);
        grupos::create(['grupo' => 'B']);
    }
}
