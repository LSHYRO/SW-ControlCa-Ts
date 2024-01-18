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

class ProfeController extends Controller
{

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
            $clases = $personal->clases;
            Log::info($clases);
            $clasesM = [];
            for ($i = 0; $i < count($clases); $i++) {
                Log::info($clases[$i]);
                $clase = clases::where('idClase', $clases[$i]->idClase)->with(['materias', 'grados', 'grupos'])->first();
                array_push($clasesM, $clase);
            }

            return  $clasesM;
        } catch (Exception $e) {
            Log::info($e);
            return ['clases' => 'Sin asignar'];
        }
    }

    public function obtenerDatosMateria($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['materias'])->first();
            return $clases->materias;
        } catch (Exception $e) {
            Log::info($e);
            return ['materias' => 'Sin asignar'];
        }
    }

    public function obtenerDatosGrado($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['grados'])->first();
            return $clases->grados;
        } catch (Exception $e) {
            Log::info($e);
            return ['grados' => 'Sin asignar'];
        }
    }
    public function obtenerDatosGrupo($idClase)
    {
        try {
            $clases = clases::where('idClase', $idClase)->with(['grupos'])->first();
            return $clases->grupos;
        } catch (Exception $e) {
            Log::info($e);
            return ['grupos' => 'Sin asignar'];
        }
    }

    public function perfil()
    {
        try {
            $usuario = auth()->user();
            $personal = personal::where('idUsuario', $usuario->idUsuario)->with(['generos', 'tipo_sangre', 'direcciones'])->first();

            $personal->domicilio = $personal->direcciones->calle . " #" . $personal->direcciones->numero . ", " . $personal->direcciones->asentamientos->asentamiento
                . ", " . $personal->direcciones->asentamientos->municipios->municipio . ", " . $personal->direcciones->asentamientos->municipios->estados->estado
                . ", " . $personal->direcciones->asentamientos->codigoPostal->codigoPostal;


            return Inertia::render('Profe/Perfil', ['usuario' => $usuario, 'profesor' => $personal]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function mostrarClase($idClase)
    {
        try {
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();

            return Inertia::render('Profe/Clase', ['clase' => $clase]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function actualizarContrasenia(Request $request)
    {
        try {
            $usuario = usuarios::find($request->idUsuario);
            $user = Auth::user();
            if (Hash::check($request->password_actual, $user->password)) {
                $usuario->contrasenia = $request->password_nueva;
                $usuario->password = bcrypt($request->password_nueva);
                $usuario->save();

                return redirect()->route('profe.usuario')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('profe.usuario')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('profe.usuario')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }
}
