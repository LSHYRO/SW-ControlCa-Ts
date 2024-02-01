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
use App\Models\calificaciones_periodos;
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

use function PHPUnit\Framework\isEmpty;

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
                $fecha_ic = Carbon::parse($clase->ciclos->fecha_inicio)->format('d/m/Y');
                $fecha_fc = Carbon::parse($clase->ciclos->fecha_fin)->format('d/m/Y');
                $clase->descripcionCiclo = $fecha_ic . " - " . $fecha_fc;
                $periodosC = periodos::where('idCiclo', $clase->idCiclo)->get();
                $periodos = $periodosC->map(function ($periodo) {
                    $periodo->fecha_ini = Carbon::parse($periodo->fecha_inicio)->format('d-m-Y');
                    $periodo->fecha_f = Carbon::parse($periodo->fecha_fin)->format('d-m-Y');
                    $periodo->descripcion = $periodo->periodo . ": " . $periodo->fecha_ini . " - " . $periodo->fecha_f;
                    return $periodo;
                });
                $actividadesCompleto = actividades::where('idClase', $clase->idClase)->get();

                $tiposActividadesAlum = tiposActividades::where('tipoActividad', 'Asistencia')
                    ->orWhere('tipoActividad', 'Vestuario')->get();
                $actividadesCA = actividades::where('idClase', $clase->idClase)
                    ->whereHas('tiposActividades', function ($query) {
                        $query->where('tipoActividad', ['Asistencia', 'Vestuario']);
                    })
                    ->get();
                $actividadesAlum = $actividadesCA->map(function ($actividad) {
                    $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
                    $actividad->periodos->fecha_ini = Carbon::parse($actividad->periodos->fecha_inicio)->format('d-m-Y');
                    $actividad->periodos->fecha_f = Carbon::parse($actividad->periodos->fecha_fin)->format('d-m-Y');
                    $actividad->periodos->descripcion = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_ini . " - " . $actividad->periodos->fecha_f;
                    $actividad->periodo = $actividad->periodos;
                    $actividad->tipoActividadD = $actividad->tiposActividades->tipoActividad;
                    return $actividad;
                });

                $tiposActividades = tiposActividades::whereNotIn('tipoActividad', ['Asistencia', 'Vestuario'])->get();

                $actividadesC = actividades::where('idClase', $clase->idClase)
                    ->whereHas('tiposActividades', function ($query) {
                        $query->whereNotIn('tipoActividad', ['Asistencia', 'Vestuario']);
                    })
                    ->get();
                $actividades = $actividadesC->map(function ($actividad) {
                    $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
                    $actividad->periodos->fecha_ini = Carbon::parse($actividad->periodos->fecha_inicio)->format('d-m-Y');
                    $actividad->periodos->fecha_f = Carbon::parse($actividad->periodos->fecha_fin)->format('d-m-Y');
                    $actividad->periodos->descripcion = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_ini . " - " . $actividad->periodos->fecha_f;
                    $actividad->periodo = $actividad->periodos;
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
                    'alumnos' => $alumnos,
                    'actividadesCompleto' => $actividadesCompleto,
                    'tiposActividadesAlum' => $tiposActividadesAlum,
                    'actividadesAlum' => $actividadesAlum,


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

    public function actualizarActividad(Request $request)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $request->clase)->where('idPersonal', $personalDocente->idPersonal)->first();
        $actividad = actividades::where('idClase', $request->clase)->where('idActividad', $request->idActividad)->first();
        if ($clase && $actividad) {
            $actividad = actividades::find($request->idActividad);
            $actividad->titulo = $request->titulo;
            $actividad->descripcion = $request->descripcion;
            $actividad->fecha_inicio = $request->fecha_inicio;
            $actividad->fecha_entrega = $request->fecha_entrega;
            $actividad->idPeriodo = $request->periodo['idPeriodo'];
            $actividad->idClase = $request->idClase;
            $actividad->idTipoActividad = $request->tipoActividad;
            $actividad->save();
            return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Actividad actualizada correctamente", "color" => "green"]);
        } else {
            return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
        }
    }

    public function eliminarActividad($idClase, $idActividad)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        $actividad = actividades::where('idClase', $idClase)->where('idActividad', $idActividad)->first();
        if ($clase && $actividad) {
            $calificaciones = calificaciones::where('idClase', $clase->idClase)
                ->where('idActividad', $actividad->idActividad)
                ->get();
            if (!$calificaciones->isEmpty()) {
                foreach ($calificaciones as $calificacion) {
                    $calificacion->delete();
                }
            }
            $actividad = actividades::find($idActividad);
            $actividad->delete();
            return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Actividad eliminada correctamente", "color" => "green"]);
        } else {
            return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
        }
    }

    public function agregarPaseLista(Request $request)
    {
        try {
            $actividad = new actividades();
            $actividad->titulo = "Pase de lista de la fecha " . Carbon::parse($request->fecha_inicio)->format('d-m-Y');
            $actividad->fecha_inicio = $request->fecha_inicio;
            $actividad->fecha_entrega = $request->fecha_inicio;
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
                return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "El pase de lista ya existe", "color" => "red"]);
            }
            $actividad->save();
            return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Pase de lista agregado correctamente", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Error al crear actividad", "color" => "red"]);
        }
    }

    public function actualizarPaseLista(Request $request)
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
            $clase = clases::where('idClase', $request->clase)->where('idPersonal', $personalDocente->idPersonal)->first();
            $actividad = actividades::where('idClase', $request->clase)->where('idActividad', $request->idActividad)->first();
            if ($clase && $actividad) {
                $actividad = actividades::find($request->idActividad);
                $actividad->titulo = "Pase de lista de la fecha " . Carbon::parse($request->fecha_inicio)->format('d-m-Y');
                $actividad->fecha_inicio = $request->fecha_inicio;
                $actividad->fecha_entrega = $request->fecha_inicio;
                $actividad->idPeriodo = $request->periodo['idPeriodo'];
                $actividad->idClase = $request->idClase;
                $actividad->idTipoActividad = $request->tipoActividad;
                $actividad->save();
                return redirect()->route('profe.mostrarClase', $request->idClase)->with(['message' => "Pase de lista actualizada correctamente", "color" => "green"]);
            } else {
                return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
            }
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
            $actividad->tipAct = $actividad->tiposActividades->tipoActividad;
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            //$clases_alumnos = $clase->clases_alumnos()->get();            
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
            if (!$calificaciones->isEmpty()) {
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
            if (!$calificacionesBD->isEmpty()) {
                foreach ($request->calificaciones as $alumnoId => $calificacionB) {
                    $calificacionA = calificaciones::where('idClase', $request->clase)
                        ->where('idActividad', $request->actividad)
                        ->where('idAlumno', $alumnoId)
                        ->first();
                    if (!$calificacionA) {
                        $calificacionA = new calificaciones();
                        $calificacionA->idClase = $request->clase;
                        $calificacionA->idActividad = $request->actividad; // Ajusta según tu lógica
                        $calificacionA->idAlumno = $alumnoId;
                        $calificacionA->calificacion = $calificacionB;
                        $calificacionA->save();
                    } else {
                        $calificacionA->calificacion = $calificacionB;
                        $calificacionA->save();
                    }
                }
            } else {
                foreach ($request->calificaciones as $alumnoId => $calificacion) {
                    if ($calificacion != null) {
                        $calificacionA = new calificaciones();
                        $calificacionA->idClase = $request->clase;
                        $calificacionA->idActividad = $request->actividad; // Ajusta según tu lógica
                        $calificacionA->idAlumno = $alumnoId;
                        $calificacionA->calificacion = $calificacion;
                        $calificacionA->save();
                    }
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
            $actividad->tipAct = $actividad->tiposActividades->tipoActividad;
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

    public function paseLista($idClase, $idActividad)
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
            $actividad->tipAct = $actividad->tiposActividades->tipoActividad;
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            //$clases_alumnos = $clase->clases_alumnos()->get();            
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
            if (!$calificaciones->isEmpty()) {
                $calificacionesArray = $calificaciones->pluck('calificacion', 'idAlumno')->toArray();
                return Inertia::render('Profe/PaseLista', [
                    'actividad' => $actividad,
                    'usuario' => $usuario,
                    'clase' => $clase,
                    'alumnos' => $alumnos,
                    'calificaciones' => $calificacionesArray,
                ]);
            }
            return Inertia::render('Profe/PaseLista', [
                'actividad' => $actividad,
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnos
            ]);
        }
    }

    public function mostrarPaseLista($idClase, $idActividad)
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
            $actividad->tipAct = $actividad->tiposActividades->tipoActividad;
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();


            $calificacionesArray = $calificaciones->pluck('calificacion', 'idAlumno')->toArray();
            // Asignar "Sin calificar" a los alumnos que no tienen calificación
            $alumnosConCalificaciones = $alumnos->map(function ($alumno) use ($calificacionesArray) {
                $alumno->calificacion = $calificacionesArray[$alumno->idAlumno] ?? 'Sin puntuar';
                return $alumno;
            });

            return Inertia::render('Profe/VerPaseLista', [
                'actividad' => $actividad,
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnos,
                'calificaciones' => $alumnosConCalificaciones,
            ]);
        }
    }

    public function obtenerTiposActividades($idClase, $idPeriodo)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $tiposDeActividad = actividades::where('idPeriodo', $idPeriodo)
                ->where('idClase', $idClase)
                ->distinct('idTipoActividad')
                ->pluck('idTipoActividad');
            $detallesTiposDeActividad = tiposActividades::whereIn('idTipoActividad', $tiposDeActividad)
                ->get();
            return response()->json($detallesTiposDeActividad);
        } else {
            return redirect('/profesor')->with('error', 'No tiene permisos para acceder a esta funcionalidad.');
        }
    }

    public function calificarPeriodo(Request $request)
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
            $clase = clases::where('idClase', $request->idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
            if ($clase) {
                $tipos_actividad = $request->calTipoAct;
                $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
                $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
                $calificaciones_periodosA = calificaciones_periodos::where('idClase', $clase->idClase)
                    ->where('idPeriodo', $request->periodo['idPeriodo'])
                    ->get();
                if (!$calificaciones_periodosA->isEmpty()) {
                    for ($index = 0; $index < count($alumnos); $index++) {
                        $cal_periodo = calificaciones_periodos::where('idClase', $clase->idClase)
                            ->where('idAlumno', $alumnos[$index]->idAlumno)
                            ->where('idPeriodo', $request->periodo['idPeriodo'])
                            ->first();
                        if (!$cal_periodo) {
                            $calificacionPeriodo = 0;
                            foreach ($tipos_actividad as $tipoActividadId => $porcentaje) {
                                $sumaCalificacionTipoAct = 0;
                                $actividades = actividades::where('idClase', $clase->idClase)
                                    ->where('idPeriodo', $request->periodo['idPeriodo'])
                                    ->where('idTipoActividad', $tipoActividadId)
                                    ->get();
                                for ($jIndex = 0; $jIndex < count($actividades); $jIndex++) {
                                    $calificacion = calificaciones::where('idClase', $clase->idClase)
                                        ->where('idActividad', $actividades[$jIndex]->idActividad)
                                        ->where('idAlumno', $alumnos[$index]->idAlumno)
                                        ->first();
                                    $sumaCalificacionTipoAct += $calificacion->calificacion;
                                }
                                $calificacionTipoAct = $sumaCalificacionTipoAct / count($actividades);
                                $calificacionPeriodo += ($calificacionTipoAct * $porcentaje) / 100;
                            }
                            $calificaciones_periodos = new calificaciones_periodos();
                            $calificaciones_periodos->idClase = $clase->idClase;
                            $calificaciones_periodos->idPeriodo = $request->periodo['idPeriodo'];
                            $calificaciones_periodos->idAlumno = $alumnos[$index]->idAlumno;
                            $calificaciones_periodos->calificacion = $calificacionPeriodo;
                            $calificaciones_periodos->save();
                        } else {
                            $calificacionPeriodo = 0;
                            foreach ($tipos_actividad as $tipoActividadId => $porcentaje) {
                                $sumaCalificacionTipoAct = 0;
                                $actividades = actividades::where('idClase', $clase->idClase)
                                    ->where('idPeriodo', $request->periodo['idPeriodo'])
                                    ->where('idTipoActividad', $tipoActividadId)
                                    ->get();
                                for ($jIndex = 0; $jIndex < count($actividades); $jIndex++) {
                                    $calificacion = calificaciones::where('idClase', $clase->idClase)
                                        ->where('idActividad', $actividades[$jIndex]->idActividad)
                                        ->where('idAlumno', $alumnos[$index]->idAlumno)
                                        ->first();
                                    $sumaCalificacionTipoAct += $calificacion->calificacion;
                                }
                                $calificacionTipoAct = $sumaCalificacionTipoAct / count($actividades);
                                $calificacionPeriodo += ($calificacionTipoAct * $porcentaje) / 100;
                            }
                            $cal_periodo->calificacion = $calificacionPeriodo;
                            $cal_periodo->save();
                        }
                    }
                    $fecha_i = Carbon::parse($request->periodo['fecha_inicio'])->format('d-m-Y');
                    $fecha_f = Carbon::parse($request->periodo['fecha_fin'])->format('d-m-Y');
                    $descripcionPeriodo = $request->periodo['periodo'] . ' => ' . $fecha_i . ' - ' . $fecha_f;
                    return redirect()->route('profe.mostrarClase', $clase->idClase)->with(['message' => "Periodo calificado actualizado: " . $descripcionPeriodo .". Recuerde volver a realizar la calificacion final en dado caso de ya haberlo hecho antes.", "color" => "green"]);
                } else {
                    //Metodo para guardarlos como si fueran nuevos
                    for ($index = 0; $index < count($alumnos); $index++) {
                        $calificacionPeriodo = 0;
                        foreach ($tipos_actividad as $tipoActividadId => $porcentaje) {
                            $sumaCalificacionTipoAct = 0;
                            $actividades = actividades::where('idClase', $clase->idClase)
                                ->where('idPeriodo', $request->periodo['idPeriodo'])
                                ->where('idTipoActividad', $tipoActividadId)
                                ->get();
                            for ($jIndex = 0; $jIndex < count($actividades); $jIndex++) {
                                $calificacion = calificaciones::where('idClase', $clase->idClase)
                                    ->where('idActividad', $actividades[$jIndex]->idActividad)
                                    ->where('idAlumno', $alumnos[$index]->idAlumno)
                                    ->first();
                                $sumaCalificacionTipoAct += $calificacion->calificacion;
                            }
                            $calificacionTipoAct = $sumaCalificacionTipoAct / count($actividades);
                            $calificacionPeriodo += ($calificacionTipoAct * $porcentaje) / 100;
                        }
                        $calificaciones_periodos = new calificaciones_periodos();
                        $calificaciones_periodos->idClase = $clase->idClase;
                        $calificaciones_periodos->idPeriodo = $request->periodo['idPeriodo'];
                        $calificaciones_periodos->idAlumno = $alumnos[$index]->idAlumno;
                        $calificaciones_periodos->calificacion = $calificacionPeriodo;
                        $calificaciones_periodos->save();
                    }

                    $fecha_i = Carbon::parse($request->periodo['fecha_inicio'])->format('d-m-Y');
                    $fecha_f = Carbon::parse($request->periodo['fecha_fin'])->format('d-m-Y');
                    $descripcionPeriodo = $request->periodo['periodo'] . ' => ' . $fecha_i . ' - ' . $fecha_f;
                    return redirect()->route('profe.mostrarClase', $clase->idClase)->with(['message' => "Periodo calificado: " . $descripcionPeriodo, "color" => "green"]);
                }
            } else {
                return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function mostrarCalificacionesPer($idClase, $idPeriodo)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $periodo = periodos::find($idPeriodo);
            $calificaciones = calificaciones_periodos::where('idClase', $idClase)
                ->where('idPeriodo', $idPeriodo)
                ->get();
            $periodo->fecha_i = Carbon::parse($periodo->fecha_inicio)->format('d-m-Y');
            $periodo->fecha_f = Carbon::parse($periodo->fecha_fin)->format('d-m-Y');
            $periodo->descripcion = $periodo->periodo . ": " . $periodo->fecha_i . " - " . $periodo->fecha_f;

            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();


            $calificacionesArray = $calificaciones->pluck('calificacion', 'idAlumno')->toArray();
            // Asignar "Sin calificar" a los alumnos que no tienen calificación
            $alumnosConCalificaciones = $alumnos->map(function ($alumno) use ($calificacionesArray) {
                $alumno->calificacion = $calificacionesArray[$alumno->idAlumno] ?? 'Sin calificar';
                return $alumno;
            });

            return Inertia::render('Profe/VerCalificacionesPer', [
                'periodo' => $periodo,
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnos,
                'calificaciones' => $alumnosConCalificaciones,
            ]);
        }
    }

    public function eliminarCalificacionesPeriodo($idClase, $idPeriodo)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $calificaciones_periodo = calificaciones_periodos::where('idClase', $idClase)
                ->where('idPeriodo', $idPeriodo)
                ->get();
            if (!$calificaciones_periodo->isEmpty()) {
                foreach ($calificaciones_periodo as $calificacion) {
                    $calificacion->delete();
                }
                return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Calificaciones del periodo eliminadas correctamente", "color" => "green"]);
            }
            return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Aun no se ha calificado este periodo", "color" => "yellow"]);
        } else {
            return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
        }
    }

    public function calificarClase($idClase)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $calificaciones_periodos = calificaciones_periodos::where('idClase', $clase->idClase)->get();
            $periodos_totales = periodos::where('idCiclo', $clase->idCiclo)->get();
            if (!$calificaciones_periodos->isEmpty()) {
                $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
                $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
                for ($index = 0; $index < count($alumnos); $index++) {
                    $cal_per = calificaciones_periodos::where('idClase', $clase->idClase)
                        ->where('idAlumno', $alumnos[$index]->idAlumno)
                        ->get();
                    $calif = 0;
                    for ($i = 0; $i < count($cal_per); $i++) {
                        $calif += $cal_per[$i]->calificacion;
                    }                    
                    $clases_alumno = clases_alumnos::where('idAlumno', $alumnos[$index]->idAlumno)
                        ->where('idClase', $clase->idClase)
                        ->first();                    
                    $cal_total_alumno = $calif / count($periodos_totales);
                    $clases_alumno->calificacionClase = round($cal_total_alumno);
                    $clases_alumno->save();
                }
                return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Calificacion final realizada", "color" => "green"]);
            }
            return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Aun no se ha calificado ningun periodo", "color" => "yellow"]);
        }
        return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
    }

    public function mostrarCalificacionClase($idClase)
    {
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $clase = clases::where('idClase', $idClase)->with(['materias'])->first();
            $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
            $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
            $cl_alumnos = clases_alumnos::where('idClase', $clase->idClase)->get();
            $fecha_ic = Carbon::parse($clase->ciclos->fecha_inicio)->format('d/m/Y');
            $fecha_fc = Carbon::parse($clase->ciclos->fecha_fin)->format('d/m/Y');
            $clase->descripcionCiclo = $fecha_ic . " - " . $fecha_fc;

            $calificacionesArray = $cl_alumnos->pluck('calificacionClase', 'idAlumno')->toArray();
            // Asignar "Sin calificar" a los alumnos que no tienen calificación
            $alumnosConCalificaciones = $alumnos->map(function ($alumno) use ($calificacionesArray) {
                $alumno->calificacion = $calificacionesArray[$alumno->idAlumno] ?? 'Sin calificar';
                return $alumno;
            });
            return Inertia::render('Profe/VerCalificacionFinal', [
                'usuario' => $usuario,
                'clase' => $clase,
                'alumnos' => $alumnosConCalificaciones,
            ]);
        }
        return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
    }

    public function eliminarCalificacionFinal ($idClase){
        $usuario = $this->obtenerInfoUsuario();
        $personalDocente = personal::where('idUsuario', $usuario->idUsuario)->first();
        $clase = clases::where('idClase', $idClase)->where('idPersonal', $personalDocente->idPersonal)->first();
        if ($clase) {
            $calificaciones_periodos = calificaciones_periodos::where('idClase', $clase->idClase)->get();            
            if (!$calificaciones_periodos->isEmpty()) {
                $idsAlumnos = $clase->clases_alumnos()->pluck('idAlumno');
                $alumnos = Alumnos::whereIn('idAlumno', $idsAlumnos)->get();
                for ($index = 0; $index < count($alumnos); $index++) {                           
                    $clases_alumno = clases_alumnos::where('idAlumno', $alumnos[$index]->idAlumno)
                        ->where('idClase', $clase->idClase)
                        ->first();                                        
                    $clases_alumno->calificacionClase = null;
                    $clases_alumno->save();
                }
                return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Calificaciones finales eliminadas", "color" => "green"]);
            }
            return redirect()->route('profe.mostrarClase', $idClase)->with(['message' => "Aun no se ha calificado ningun periodo", "color" => "yellow"]);
        }
        return redirect()->route('profe.inicio')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
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
