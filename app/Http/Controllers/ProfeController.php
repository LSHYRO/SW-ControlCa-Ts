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
        $grupos = grupos::all();
        $grados = grados::all();
        $materias = materias::all();
        $ciclos = ciclos::all();
        $clases = clases::all();
        //$personal = personal::all();
    
        // Obtener el personal autenticado
        $personalAutenticado = auth()->user()->personal;
    
        // Verificar si el personal tiene clases asociadas
        if ($personalAutenticado) {
            $clases = $personalAutenticado->clases;
        } else {
            $clases = []; // Otra lógica si el personal no tiene clases
        }
    
        return Inertia::render('Profe/Clases', [
            'clases' => $clases,
            'grupos' => $grupos,
            'grados' => $grados,
            'personal' => $personalAutenticado,
            'materias' => $materias,
            'ciclos' => $ciclos,
        ]);
    }
    
public function obtenerPersonal($idUsuario)
{
    // Modifica esto según la relación en tu base de datos
    $personal = personal::where('idUsuario', $idUsuario)->get();
    dd($personal);
    return response()->json($personal);
}

public function obtenerDatosClase($idPersonal)
    {
        try {
            $personal = personal::where('idPersonal', $idPersonal)->with(['clases'])->first();
            return $personal->clases;
        } catch (Exception $e) {
            Log::info($e);
            return ['clases'=>'Sin asignar'];
        }
    }
    public function obtenerDatosMateria($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['materias'])->first();
            return $clases->materias;
        } catch (Exception $e) {
            Log::info($e);
            return ['materias'=>'Sin asignar'];
        }
    }
    public function obtenerDatosGrado($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['grados'])->first();
            return $clases->grados;
        } catch (Exception $e) {
            Log::info($e);
            return ['grados'=>'Sin asignar'];
        }
    }
    public function obtenerDatosGrupo($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['grupos'])->first();
            return $clases->grupos;
        } catch (Exception $e) {
            Log::info($e);
            return ['grupos'=>'Sin asignar'];
        }
    }

}