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
        $tipo_personal1 -> tipo_personal = "director";
        $tipo_personal1 -> save();

        $tipo_personal2 = new tipo_personal();
        $tipo_personal2 -> tipo_personal = "personal escolar";
        $tipo_personal2 -> save();

        $tipo_personal3 = new tipo_personal();
        $tipo_personal3 -> tipo_personal = "profesor";
        $tipo_personal3 -> save();
    }
}
