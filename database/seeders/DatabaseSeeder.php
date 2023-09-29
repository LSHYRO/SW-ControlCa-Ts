<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\materias;
use App\Models\personas;
use App\Models\profesores;
use App\Models\usuarios;
use App\Models\usuarios_tiposUsuarios;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(EstadosSeeder::class);
        $this->call(TiposUsuariosSeeder::class);
        
        usuarios::factory(50)->create();
        usuarios_tiposUsuarios::factory(50)->create();
        materias::factory(30)->create();
        personas::factory(50)->create();
        profesores::factory(15)->create();
    }
}
