<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\actividades;
use App\Models\tiposActividades;
use App\Models\calificaciones;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\alumnos;
use App\Models\materias;
use App\Models\clases;
use App\Models\clases_alumnos;
use App\Models\ciclos;
use App\Models\periodos;
use App\Models\tutores;
use App\Models\direcciones;
use App\Models\estados;
use App\Models\generos;
use App\Models\grados;
use App\Models\grupos;
use App\Models\personal;
use App\Models\alumnos_escolar;
use App\Models\tipo_alumnos;
use App\Models\tipo_Sangre;
use App\Models\tipoUsuarios;
use App\Models\usuarios_tiposUsuarios;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
use Mockery\Undefined;

class AlumnoController extends Controller{

    public function inicio()
    {
        $usuario = $this->obtenerInfoUsuario();
        $message = '';
        $color = '';

        if ($usuario->cambioContrasenia === 0) {
            $fechaLimite = Carbon::parse($usuario->fecha_Creacion)->addHours(48);
            $fechaFormateada = $fechaLimite->format('d/m/Y');
            $horaFormateada = $fechaLimite->format('H:i');
            $message = "Tiene hasta el " . $fechaFormateada . " a las " . $horaFormateada . " hrs para realizar el cambio de contraseña, en caso contrario, esta se desactivara y sera necesario acudir a la dirección para solucionar la situación";
            $color = "red";
            return Inertia::render('Alumno/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color]);
        }


        return Inertia::render('Alumno/Inicio', [
            'usuario' => $usuario, 'message' => $message, 'color' => $color
        ]);
    }

    public function obtenerInfoUsuario()
    {
        $idUsuario = auth()->user()->idUsuario;
        $usuario = usuarios::where('idUsuario', $idUsuario)->with(['alumnos'])->first();
        $usuario->tipoUsuario5 = $usuario->tipoUsuarios->tipoUsuario;
        $usuario->alumnoNombre = $usuario->alumnos->nombre_completo;

        return $usuario;
    }

    public function perfil()
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $alumnos = alumnos::where('idUsuario', $usuario->idUsuario)->with(['generos', 'tipo_sangre', 'direcciones'])->first();

            $alumnos->domicilio = $alumnos->direcciones->calle . " #" . $alumnos->direcciones->numero . ", " . $alumnos->direcciones->asentamientos->asentamiento
                . ", " . $alumnos->direcciones->asentamientos->municipios->municipio . ", " . $alumnos->direcciones->asentamientos->municipios->estados->estado
                . ", " . $alumnos->direcciones->asentamientos->codigoPostal->codigoPostal;
         
            return Inertia::render('Alumno/Perfil', [
                'usuario' => $usuario, 
                'estudiante' => $alumnos,
            ]);
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
                $usuario->cambioContrasenia = 1;
                $usuario->save();

                return redirect()->route('alumno.perfil')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('alumno.perfil')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('alumno.perfil')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }

    public function clases()
    {
        $grupos = grupos::all();
        $grados = grados::all();
        $ciclos = ciclos::all();
        $usuario = $this->obtenerInfoUsuario();
        $clases = $this->obtenerDatosClase($usuario->alumnos->idAlumno);//aquí está el error
        $calificaciones= $this->obtenerDatosCalificaciones($usuario->alumnos->idAlumno);

        return Inertia::render('Alumno/EnCurso', [
            'clases' => $clases,
            //'clases_alumnos'=> $clases_alumnos,
            'grupos' => $grupos,
            'grados' => $grados,
            'ciclos' => $ciclos,
            'usuario' => $usuario,
            'calificaciones'=> $calificaciones,
        ]);
    }

    public function curso()
    {
        $grupos = grupos::all();
        $grados = grados::all();
        $ciclos = ciclos::all();
        $usuario = $this->obtenerInfoUsuario();
        $clases = $this->obtenerDatosClase($usuario->alumnos->idAlumno);//aquí está el error
        $calificaciones= $this->obtenerDatosCalificaciones($usuario->alumnos->idAlumno);
        $cali = calificaciones::all();

        dd($cali);

        return Inertia::render('Alumno/Curso', [
            'clases' => $clases,
            'grupos' => $grupos,
            'grados' => $grados,
            'ciclos' => $ciclos,
            'usuario' => $usuario,
            'calificaciones'=> $calificaciones,
            'cali'=> $cali,
        ]);
    }

    public function obtenerDatosCalificaciones($idAlumno)
{
    try {
        $alumno = alumnos::where('idAlumno', $idAlumno)->first();
        $actividadesA = actividades::where('idAlumno', $alumno->idAlumno)->get();
        Log::info($actividadesA);
        //dd($actividadesA);
        $actividadesM = [];
        for ($i = 0; $i < count($actividadesA); $i++) {
            Log::info($actividadesA[$i]);
            $calificacion = calificaciones::where('idCalificacion', $actividadesA[$i]->idCalificacion)->first();
            array_push($actividadesM, $calificacion);
        }

        return $actividadesM;
    } catch (\Exception $e) {
        Log::info($e);
    }
}



    public function obtenerAlumno($idUsuario)
    {
        // Modifica esto según la relación en tu base de datos
        $alumnos = alumnos::where('idUsuario', $idUsuario)->get();
        dd($alumnos);
        return response()->json($alumnos);
    }

    public function obtenerDatosClase($idAlumno)//El error es aquí
    {
        try {
            $alumnos = alumnos::where('idAlumno', $idAlumno)->first();
            $clasesA = clases_alumnos::where('idAlumno',$alumnos->idAlumno)->get();
            //dd($alumnos);            //$clasesA = $alumnos->clases_alumnos->toArray();
            Log::info($clasesA);
            $clasesM = [];
            for ($i = 0; $i < count($clasesA); $i++) {
                Log::info($clasesA[$i]);
                $clase = clases::where('idClase', $clasesA[$i]->idClase)->with(['materias', 'grados', 'grupos'])->first();
                //$clase = clases_alumnos::where('idClase', $clasesA[$i]->idClase)->first();
                array_push($clasesM, $clase);
            }
            //dd($clasesA);
            return  $clasesM;
        } catch (Exception $e) {
            Log::info($e);
            return ['clases_alumnos' => 'Sin asignar'];
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

    public function mostrarClase($idClase)
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $alumno = alumnos::where('idUsuario', $usuario->idUsuario)->first();
            $clasesA = clases_alumnos::where('idClase',$idClase)->where('idAlumno',$alumno->idAlumno)->first();
            if ($clasesA) {
                $clasesA = clases::where('idClase', $idClase)->with(['materias'])->first();

                $actividadesC = actividades::where('idClase', $clasesA->idClase)->get();

                $actividades = $actividadesC->map(function ($actividad)use($clasesA,$alumno) {
                    $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
                    $actividad->periodos->fecha_ini = Carbon::parse($actividad->periodos->fecha_inicio)->format('d-m-Y');
                    $actividad->periodos->fecha_f = Carbon::parse($actividad->periodos->fecha_fin)->format('d-m-Y');
                    $actividad->periodos->descripcion = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_ini . " - " . $actividad->periodos->fecha_f;
                    $actividad->periodo = $actividad->periodos;
                    $actividad->tipoActividadD = $actividad->tiposActividades->tipoActividad;

                    $calificacion = calificaciones::where('idClase', $clasesA->idClase)
                        ->where('idActividad', $actividad->idActividad)
                        ->where('idAlumno', $alumno->idAlumno)
                        ->first();

                        if($calificacion){
                            $actividad->calificacion = $calificacion->calificacion;
                            }else{
                            $actividad->calificacion = "Sin calificar";
                            }

                    return $actividad;
                });

                //dd($actividades);

                return Inertia::render('Alumno/Curso', [
                    'clasesA' => $clasesA,
                    'usuario' => $usuario,
                    'actividades' => $actividades,
                    'actividadesC' => $actividadesC,
                    'alumno' => $alumno,
                ]);
            } else {
                return redirect()->route('alumno.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}