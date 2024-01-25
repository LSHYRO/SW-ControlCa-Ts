<?php

namespace Database\Seeders;

use App\Models\tiposActividades;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposActividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposActividades1 = new tiposActividades();
        $tiposActividades1->tipoActividad = "Tarea";
        $tiposActividades1->save();

        $tiposActividades2 = new tiposActividades();
        $tiposActividades2->tipoActividad = "Actividad en clase";
        $tiposActividades2->save();

        $tiposActividades3 = new tiposActividades();
        $tiposActividades3->tipoActividad = 'Proyecto';
        $tiposActividades3->save();

        $tiposActividades4 = new tiposActividades();
        $tiposActividades4->tipoActividad = 'Examen';
        $tiposActividades4->save();

        $tiposActividades5 = new tiposActividades();
        $tiposActividades5->tipoActividad = 'Asistencia';
        $tiposActividades5->save();

        $tiposActividades6 = new tiposActividades();
        $tiposActividades6->tipoActividad = 'Vestuario';
        $tiposActividades6->save();

    }
}
