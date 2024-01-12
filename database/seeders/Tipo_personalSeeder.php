<?php

namespace Database\Seeders;

use App\Models\tipo_personal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Tipo_personalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo_personal1 = new tipo_personal();
        $tipo_personal1 -> tipo_personal = "Director";
        $tipo_personal1 -> save();

        $tipo_personal2 = new tipo_personal();
        $tipo_personal2 -> tipo_personal = "Personal escolar";
        $tipo_personal2 -> save();

        $tipo_personal3 = new tipo_personal();
        $tipo_personal3 -> tipo_personal = "Profesor";
        $tipo_personal3 -> save();
    }
}
