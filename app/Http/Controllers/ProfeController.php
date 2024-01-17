<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\actividades;
use App\Models\tiposActividades;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\alumnos;
use App\Models\materias;
use App\Models\clases;
use App\Models\ciclos;
use App\Models\periodos;
use App\Models\tutores;
use App\Models\direcciones;
use App\Models\estados;
use App\Models\generos;
use App\Models\grados;
use App\Models\grupos;
use App\Models\personal;
use App\Models\personal_escolar;
use App\Models\tipo_personal;
use App\Models\tipo_Sangre;
use App\Models\tipoUsuarios;
use App\Models\usuarios_tiposUsuarios;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
use Mockery\Undefined;

class ProfeController extends Controller{

    public function inicio()
    {
        return Inertia::render('Profe/Inicio');
    }

    public function actividades()
    {
        $actividades = actividades::all();
        $clases = clases::all();
        $periodos = periodos::all();
        $tiposActividades = tiposActividades::all();
        return Inertia::render('Profe/Actividades', ['actividades' => $actividades]);
    }

    public function clases()
    {

        $personal = Personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->where('tipo_personal.tipo_personal', 'Profesor')//Le puse con mayuscula la P
            ->get();

        $clases = clases::all();
        $grupos = grupos::all();
        $grados = grados::all();
        //$personal = personal::all();
        $materias = materias::all();
        $ciclos = ciclos::all();

        return Inertia::render('Profe/Clases', [
            'clases' => $clases,
            'grupos' => $grupos,
            'grados' => $grados,
            'personal' => $personal,
            'materias' => $materias,
            'ciclos' => $ciclos,
        ]);
    }
    
}