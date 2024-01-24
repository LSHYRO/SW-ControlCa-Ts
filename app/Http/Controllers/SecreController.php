<?php

namespace App\Http\Controllers;

use App\Models\personas;
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

class SecreController extends Controller{

    public function inicio()
    {
        return Inertia::render('Secre/Inicio');
    }

    public function cuentas()
    {
        $usuarios = usuarios::whereHas('tipoUsuarios', function ($query) {
        $query->whereIn('tipoUsuario', ['profesor','estudiante','tutor']);//Para ver cuentas solo de esos tipos de usuarios
        })
        ->get();

        return Inertia::render('Director/Cuentas', [
            'usuarios' => $usuarios,
        ]);
    }

    public function addCuentas(Request $request)
    {
        $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'administrador')->first();
        $usuario = new usuarios();
        $usuario->usuario = $request->usuario;
        $usuario->contrasenia = $request->contrasenia;
        $usuario->password = bcrypt($request->contrasenia);
        $usuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;

        $usuario->save();
        return redirect()->route('director.cuentas')->with('message', "Usuario agregado correctamente: " . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||");
    }

    public function elimCuentas($usuariosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $usuariosIdsArray = explode(',', $usuariosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $usuariosIdsArray = array_map('intval', $usuariosIdsArray);

            // Elimina los ciclos
            usuarios::whereIn('idUsuario', $usuariosIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.usuarios')->with('message', "Usuarios eliminados correctamente");
        } catch (\Exception $e) {
            // Manejo de errores
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function eliminarCuentas($idUsuario)
    {
        $usuario = usuarios::find($idUsuario);
        $usuario->delete();
        return redirect()->route('director.cuentas')->with('message', "Usuario eliminado correctamente");
    }

    public function actualizarCuentas(Request $request, $idUsuario)
    {
        try {
            $usuarios = usuarios::find($idUsuario);
            $request->validate([
                'usuario' => 'required',
                'contrasenia' => 'required',
            ]);
            $usuarios->usuario = $request->usuario;
            $usuarios->contrasenia = $request->contrasenia;

            $usuarios->fill($request->input())->saveOrFail();
        } catch (Exception $e) {
            dd($e);
        }
        return redirect()->route('director.cuentas')->with('message', "Usuario actualizado correctamente: " . $usuarios->usuario);;
    }

    public function alumnosclases()
    {

        $clases = clases::all();
        $alumnos = alumnos::all();
        $materias = materias::all();
        $grados = grados::all();
        $grupos = grupos::all();
        $clases_alumnos = clases_alumnos::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Secre/AlumnosClase', [
            'clases' => $clases,
            'alumnos' => $alumnos,
            'materias' => $materias,
            'usuario' => $usuario,
            'grados' => $grados,
            'grupos' => $grupos,
            'clases_alumnos' => $clases_alumnos,
        ]);
    }

    public function addAlumnosClases(Request $request)
{
    try {
        $request->validate([
            'clase' => 'required',
            'alumno' => 'required|array', // Ahora esperamos un array de alumnos
        ]);

        $clase_id = $request->clase;
        $alumnos = $request->alumno;

        foreach ($alumnos as $alumno_id) {
            $clase_alumno = new clases_alumnos();
            $clase_alumno->idClase = $clase_id;
            $clase_alumno->idAlumno = $alumno_id;
            $clase_alumno->calificacionClase = 0;
            $clase_alumno->save();
        }
    } catch (Exception $e) {
        dd($e);
    }

    return redirect()->route('secre.alumnosclases')->with('message', "Alumnos agregados correctamente");
}


    public function eliminarAlumnosClases($idClaseAlumno)
    {
        $clase_alumno = clases_alumnos::find($idClaseAlumno);
        $clase_alumno ->delete();
        return redirect()->route('secre.alumnosclases')->with('message', "Clase eliminada correctamente");
    }

    public function elimAlumnosClases($clases_alumnosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $clasesAlumnosIdsArray = explode(',', $clases_alumnosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $clasesAlumnosIdsArray = array_map('intval', $clasesAlumnosIdsArray);

            // Elimina los ciclos
            clases_alumnos::whereIn('idClaseAlumno', $clasesAlumnosIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('secre.alumnosclases')->with('message', "Clases eliminadas correctamente");
        } catch (\Exception $e) {
            // Manejo de errores
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }
    
}