<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\materias;
use App\Models\personal;
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
        //$this->call(EstadosSeeder::class);
        //$this->call(MunicipiosSeeder::class);
        $this->call(TiposUsuariosSeeder::class);
        $this->call(Tipo_personalSeeder::class);
        $this->call(TipoSangreSeeder::class);
        $this->call(GeneroSeeder::class);
        $this->call(TiposActividadesSeeder::class);
        $this->call(GradosGruposSeeder::class);
        //$this->call(AsentamientosSeeder::class);
        
        //usuarios::factory(50)->create();
        //usuarios_tiposUsuarios::factory(50)->create();
        //materias::factory(30)->create();
        //personal::factory(50)->create();
    }
}
