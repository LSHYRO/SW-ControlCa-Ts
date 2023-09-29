<?php

namespace Database\Seeders;

use App\Models\tipoUsuarios;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoUsuario1 = new tipoUsuarios();
        $tipoUsuario1->tipoUsuario = "administrador";
        $tipoUsuario1->save();

        $tipoUsuario2 = new tipoUsuarios();
        $tipoUsuario2->tipoUsuario = "director";
        $tipoUsuario2->save();

        $tipoUsuario3 = new tipoUsuarios();
        $tipoUsuario3->tipoUsuario = "directivo";
        $tipoUsuario3->save();

        $tipoUsuario4 = new tipoUsuarios();
        $tipoUsuario4->tipoUsuario = 'profesor';
        $tipoUsuario4->save();

        $tipoUsuario5 = new tipoUsuarios();
        $tipoUsuario5->tipoUsuario = 'estudiante';
        $tipoUsuario5->save();

        $tipoUsuario6 = new tipoUsuarios();
        $tipoUsuario6->tipoUsuario = "tutor";
        $tipoUsuario6->save();
    }
}
