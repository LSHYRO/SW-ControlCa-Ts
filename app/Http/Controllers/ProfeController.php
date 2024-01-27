<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\actividades;
use App\Models\tiposActividades;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\alumnos;
use App\Models\calificaciones;
use App\Models\materias;
use App\Models\clases;
use App\Models\ciclos;
use App\Models\clases_alumnos;
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
use Illuminate\Support\Carbon;
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
    public function obtenerInfoUsuario()
    {
        $idUsuario = auth()->user()->idUsuario;
        $usuario = usuarios::where('idUsuario', $idUsuario)->with(['personal'])->first();
        $usuario->tipoUsuario1 = $usuario->tipoUsuarios->tipoUsuario;
        $usuario->personalNombre = $usuario->personal->nombre_completo;

        return $usuario;
    }

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
            return Inertia::render('Profe/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color]);
        }


        return Inertia::render('Profe/Inicio', [
            'usuario' => $usuario, 'message' => $message, 'color' => $color
        ]);
    }

    public function actividades()
    {
        $actividades = actividades::all();
        $clases = clases::all();
        $periodos = periodos::all();
        $tipoActividad = tiposActividades::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Profe/Clases', [
            'actividades' => $actividades,
            'periodos' => $periodos,
            'tipoActividad' => $tipoActividad,
            'usuario' => $usuario
        ]);
    }

    public function actividadesClase()
    {                                       //Esto es para agregar actividades
        $actividades = actividades::all();
        $clases = clases::all();
        $periodos = periodos::all();
        $tipoActividad = tiposActividades::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Profe/Clase', [
            'actividades' => $actividades,
            'clases' => $clases,
            'periodos' => $periodos,
            'tipoActividad' => $tipoActividad,
            'usuario' => $usuario
        ]);
    }

    public function addActividades(Request $request)
    {
        try {
            $request->validate([
                'descripcion' => 'required',
                'clases' => 'required',
                'periodos' => 'required',
                'tipoActividad' => 'required',
            ]);
            //$actividad -> idClase = $request->idClase;

            $periodos = new periodos();

            $actividad = new actividades();
            $actividad->descripcion = $request->descripcion;
            $actividad->idClase = $request->clases;
            $actividad->idPeriodo = $request->periodos;
            $actividad->idTipoActividad = $request->tipoActividad;

            $actividad->save();
        } catch (Exception $e) {
        }
        return redirect()->route('profe.actividadesClase')->with('message', "Actividad agregada correctamente: " . $actividad->descripcion);
    }

    public function clases()
    {
        $grupos = grupos::all();
        $grados = grados::all();
        $ciclos = ciclos::all();
        $usuario = $this->obtenerInfoUsuario();
        $clases = $this->obtenerDatosClase($usuario->personal->idPersonal);


        return Inertia::render('Profe/Clases', [
            'clases' => $clases,
            'grupos' => $grupos,
            'grados' => $grados,
            'ciclos' => $ciclos,
            'usuario' => $usuario
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
            $usuario = $this->obtenerInfoUsuario();
            $personal = personal::where('idUsuario', $usuario->idUsuario)->with(['generos', 'tipo_sangre', 'direcciones'])->first();

            $personal->domicilio = $personal->direcciones->calle . " #" . $personal->direcciones->numero . ", " . $personal->direcciones->asentamientos->asentamiento
                . ", " . $personal->direcciones->asentamientos->municipios->municipio . ", " . $personal->direcciones->asentamientos->municipios->estados->estado
                . ", " . $personal->direcciones->asentamientos->codigoPostal->codigoPostal;


            return Inertia::render('Profe/Perfil', ['usuario' => $usuario, 'profesor' => $personal, 'usuario' => $usuario]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function mostrarClase($idClase)
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
            $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
            if ($clase) {
                $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
                $periodosC = periodos::where('idCiclo', $clase->idCiclo)->get();
                $periodos = $periodosC->map(function ($periodo) {
                    $periodo->fecha_inicio = Carbon::parse($periodo->fecha_inicio)->format('d-m-Y');
                    $periodo->fecha_fin = Carbon::parse($periodo->fecha_fin)->format('d-m-Y');
                    $periodo->descripcion = $periodo->periodo . ": " . $periodo->fecha_inicio . " - " . $periodo->fecha_fin;
                    return $periodo;
                });

                $tiposActividades = tiposActividades::all();
                $actividadesC = actividades::where('idClase', $clase->idClase)->get();
                $actividades = $actividadesC->map(function ($actividad) {
                    $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
                    $actividad->tipoActividadD = $actividad->tiposActividades->tipoActividad;
                    return $actividad;
                });

                $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
                $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();

                return Inertia::render('Profe/Clase', [
                    'clase' => $clase,
                    'usuario' => $usuario,
                    'tiposActividades' => $tiposActividades,
                    'periodos' => $periodos,
                    'actividades' => $actividades,
                    'alumnos' => $alumnos
                ]);
            } else {
                return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function agregarActividad(Request $request)
    {
        try {
            $actividad = new actividades();
            $actividad->titulo = $request->titulo;
            $actividad->descripcion = $request->descripcion;
            $actividad->fecha_inicio = $request->fecha_inicio;
            $actividad->fecha_entrega = $request->fecha_entrega;
            $actividad->idPeriodo = $request->periodo['idPeriodo'];
            $actividad->idClase = $request->idClase;
            $actividad->idTipoActividad = $request->tipoActividad;

            $actividadExistente = actividades::where('titulo', $actividad->titulo)
                ->where('descripcion', $actividad->descripcion)
                ->where('fecha_inicio', $actividad->fecha_inicio)
                ->where('fecha_entrega', $actividad->fecha_entrega)
                ->where('idClase', $actividad->idClase)
                ->where('idPeriodo', $actividad->idPeriodo)
                ->where('idTipoActividad', $actividad->idTipoActividad)
                ->first();
            if ($actividadExistente) {
                return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "La actividad ya existe", "color" => "red"]);
            }
            $actividad->save();
            return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Actividad agregada correctamente", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Error al crear actividad", "color" => "red"]);
        }
    }

    public function calificarActividad($idClase, $idActividad)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        $actividad = actividades::where('idClase', $idClase)->where('idActividad', $idActividad)->first();
        if ($clase && $actividad) {
            $calificaciones = calificaciones::where('idClase', $idClase)
                ->where('idActividad', $idActividad)
                ->get();
            $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
            $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
            $actividad->periodoD = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_inicio . " - " . $actividad->periodos->fecha_fin;
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();

            if ($calificaciones) {
                $calificacionesArray = $calificaciones->pluck('calificacion', 'idAlumno')->toArray();
                return Inertia::render('Profe/CalificarActividad', [
                    'actividad' => $actividad,
                    'usuario' => $usuario,
                    'clase' => $clase,
                    'alumnos' => $alumnos,
                    'calificaciones' => $calificacionesArray,
                ]);
            }
            return Inertia::render('Profe/CalificarActividad', [
                'actividad' => $actividad,
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnos
            ]);
        }
    }

    public function almacenarCalificaciones(Request $request)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $request->clase)->where('idPersonal', $personalDocente->idPersonal)->first();
        $actividad = actividades::where('idClase', $request->clase)->where('idActividad', $request->actividad)->first();
        if ($clase && $actividad) {
            $calificacionesBD = calificaciones::where('idClase', $request->clase)
                ->where('idActividad', $request->actividad)
                ->get();
            if ($calificacionesBD) {
                foreach ($request->calificaciones as $alumnoId => $calificacion) {
                    $calificacionA = calificaciones::where('idClase', $request->clase)
                        ->where('idActividad', $request->actividad)
                        ->where('idAlumno', $alumnoId)
                        ->first();
                    $calificacionA->calificacion = $calificacion;
                    $calificacionA->save();
                }
            } else {
                foreach ($request->calificaciones as $alumnoId => $calificacion) {
                    $calificacionA = new calificaciones();
                    $calificacionA->idClase = $request->clase;
                    $calificacionA->idActividad = $request->actividad; // Ajusta según tu lógica
                    $calificacionA->idAlumno = $alumnoId;
                    $calificacionA->calificacion = $calificacion;
                    $calificacionA->save();
                }
            }
            return redirect()->route('profe.mostrarClase', $request->clase)->with(['message' => "Actividad calificada correctamente: " . $actividad->titulo, "color" => "green"]);
        } else {
            return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
        }
    }

    public function mostrarCalificaciones($idClase, $idActividad)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        $actividad = actividades::where('idClase', $idClase)->where('idActividad', $idActividad)->first();
        if ($clase && $actividad) {
            $calificaciones = calificaciones::where('idClase', $idClase)
                ->where('idActividad', $idActividad)
                ->get();
            $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
            $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
            $actividad->periodoD = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_inicio . " - " . $actividad->periodos->fecha_fin;
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();


            $calificacionesArray = $calificaciones->pluck('calificacion', 'idAlumno')->toArray();
            // Asignar "Sin calificar" a los alumnos que no tienen calificación
            $alumnosConCalificaciones = $alumnos->map(function ($alumno) use ($calificacionesArray) {
                $alumno->calificacion = $calificacionesArray[$alumno->idAlumno] ?? 'Sin calificar';
                return $alumno;
            });

            return Inertia::render('Profe/VerCalificacion', [
                'actividad' => $actividad,
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnos,
                'calificaciones' => $alumnosConCalificaciones,
            ]);
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

                return redirect()->route('profe.usuario')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('profe.usuario')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('profe.usuario')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }
}
