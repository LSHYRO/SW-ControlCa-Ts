<?php

namespace Database\Seeders;

use App\Models\codigoPostal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CodigoPostalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codigoPostal1 = new codigoPostal();
        $codigoPostal1-> codigoPostal = 20000;
        $codigoPostal1-> idEstado = 1;
        $codigoPostal1-> idMunicipio = 1;
        $codigoPostal1->save();
    }
}
