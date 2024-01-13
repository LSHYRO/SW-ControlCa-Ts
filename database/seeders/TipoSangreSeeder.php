<?php

namespace Database\Seeders;

use App\Models\tipo_Sangre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSangreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposSangre = [
            "A positivo (A+)",
            "A negativo (A-)",
            "B positivo (B +)",
            "B negativo (B-)",
            "AB positivo (AB+)",
            "AB negativo (AB-)",
            "O positivo (O+)",
            "O negativo (O-)"
        ];
        foreach ($tiposSangre as $tipo) {
            tipo_Sangre::create(['tipoSangre' => $tipo]);
        }
    }
    
}
