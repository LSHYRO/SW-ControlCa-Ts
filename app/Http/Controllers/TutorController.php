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

class TutorController extends Controller{

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
            return Inertia::render('Tutor/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color]);
        }


        return Inertia::render('Tutor/Inicio', [
            'usuario' => $usuario, 'message' => $message, 'color' => $color
        ]);
    }

    public function obtenerInfoUsuario()
    {
        $idUsuario = auth()->user()->idUsuario;
        $usuario = usuarios::where('idUsuario', $idUsuario)->with(['tutores'])->first();
        $usuario->tipoUsuario6 = $usuario->tipoUsuarios->tipoUsuario;
        $usuario->tutorNombre = $usuario->tutores->nombre_completo;

        return $usuario;
    }

    public function obtenerTutor($idUsuario)
    {
        // Modifica esto según la relación en tu base de datos
        $tutor = tutores::where('idUsuario', $idUsuario)->get();
        dd($tutor);
        return response()->json($tutor);
    }

    public function perfil()
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $tutores = tutores::where('idUsuario', $usuario->idUsuario)->with(['generos', 'direcciones'])->first();

            $tutores->domicilio = $tutores->direcciones->calle . " #" . $tutores->direcciones->numero . ", " . $tutores->direcciones->asentamientos->asentamiento
                . ", " . $tutores->direcciones->asentamientos->municipios->municipio . ", " . $tutores->direcciones->asentamientos->municipios->estados->estado
                . ", " . $tutores->direcciones->asentamientos->codigoPostal->codigoPostal;
         
            return Inertia::render('Tutor/Perfil', [
                'usuario' => $usuario, 
                'tutor' => $tutores,
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

                return redirect()->route('tutor.perfil')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('tutor.perfil')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('tutor.perfil')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }

    public function calificaciones(){
        try {
            $usuario = $this->obtenerInfoUsuario();
            $alumnos = $this-> obtenerDatosHijos($usuario->tutores->idTutor);
            // foreach ($alumnos as $alumno) {
            //     $clases = $this->obtenerDatosClase($alumno['idAlumno']);
            // }
            $clasesPorAlumno = [];
            foreach ($alumnos as $alumno) {
            $clasesPorAlumno[$alumno['idAlumno']]['info'] = $alumno;
            $clasesPorAlumno[$alumno['idAlumno']]['clases_cursadas'] = $this->obtenerDatosClase($alumno['idAlumno']);
            }
             // Utilizar el idAlumno del primer alumno (si existe) para obtener calificaciones
            //$calificaciones = !empty($alumnos) ? $this->obtenerDatosCalificacionesHijos($alumnos[0]['idAlumno']) : [];
            //$calificaciones = $this->obtenerDatosCalificaciones($usuario->alumnos[0]['idAlumno']);
            /*$calificacionesPorAlumno = [];
            foreach ($alumnos as $alumno) {
                $calificacionesPorAlumno[$alumno['idAlumno']] = $this->obtenerDatosCalificacionesHijos($alumno['idAlumno']);
            }*/

            //dd($clasesPorAlumno);
            return Inertia::render('Tutor/Calificaciones', [
                'usuario' => $usuario,
                'alumnos' => $alumnos,
                'clasesPorAlumno' => $clasesPorAlumno,
                //'calificacionesPorAlumno' => $calificacionesPorAlumno,
            ]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function obtenerDatosHijos($idTutor){
        try{
            $tutor = tutores::where('idTutor', $idTutor)->first();
            //dd($tutor);
            $alumnos = alumnos::where('idTutor',$tutor->idTutor)->get();
           //dd($alumnos);
            Log::info($alumnos);
            $alumnosM = [];
            for ($i = 0; $i < count($alumnos); $i++){
                Log::info($alumnos[$i]);
                $alumno = alumnos::where('idAlumno',$alumnos[$i]->idAlumno)->with(['tutores'])->first();
                array_push($alumnosM, $alumno);
            }
            return $alumnosM;
        }catch(Exception $e){
            Log::info($e);
        }
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
                array_push($clasesM, $clase);
            }
            //dd($clasesA);
            return  $clasesM;
        } catch (Exception $e) {
            Log::info($e);
            return ['clases_alumnos' => 'Sin asignar'];
        }
    }

    public function mostrarClasesHijo($idClase)
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $alumnos = $this-> obtenerDatosHijos($usuario->tutores->idTutor);
            //dd($alumnos);
            $clasesPorAlumno = [];
            foreach ($alumnos as $alumno) {
            $clasesPorAlumno[$alumno['idAlumno']]['info'] = $alumno;
            $clasesPorAlumno[$alumno['idAlumno']]['clases_cursadas'] = $this->obtenerDatosClase($alumno['idAlumno']);
            }
            //dd($clasesPorAlumno);
            if ($clasesPorAlumno) {
                //dd($clasesPorAlumno);
                $clasesA = clases::where('idClase', $idClase)->with(['materias'])->first();

                $actividadesC = actividades::where('idClase', $clasesA->idClase)->get();
                //$actividadesC = actividades::where('idClase', $clasesPorAlumno[$alumno['idAlumno']]['clases_cursadas'][0]->idClase)->get();
                //dd($actividadesC);

                //dd($actividadesC);
                $actividades = $actividadesC->map(function ($actividad)use($clasesPorAlumno,$alumnos,$clasesA) {
                    $actividad->fecha_i = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $actividad->fecha_e = Carbon::parse($actividad->fecha_entrega)->format('d-m-Y');
                    $actividad->periodos->fecha_ini = Carbon::parse($actividad->periodos->fecha_inicio)->format('d-m-Y');
                    $actividad->periodos->fecha_f = Carbon::parse($actividad->periodos->fecha_fin)->format('d-m-Y');
                    $actividad->periodos->descripcion = $actividad->periodos->periodo . ": " . $actividad->periodos->fecha_ini . " - " . $actividad->periodos->fecha_f;
                    $actividad->periodo = $actividad->periodos;
                    $actividad->tipoActividadD = $actividad->tiposActividades->tipoActividad;
                    
                    $calificacionesAlumnos = [];

                    foreach ($alumnos as $alumno) {
                        $calificacion = calificaciones::where('idClase', $clasesA->idClase)
                            ->where('idActividad', $actividad->idActividad)
                            ->where('idAlumno', $alumno->idAlumno)
                            ->first();

                        if ($calificacion) {
                            $calificacionesAlumnos[$alumno->idAlumno] = $calificacion->calificacion;
                        } else {
                            $calificacionesAlumnos[$alumno->idAlumno] = "Sin calificar";
                        }
                    }

                    $actividad->calificacionesAlumnos = $calificacionesAlumnos;

                    return $actividad;
                });

                return Inertia::render('Tutor/Curso', [
                    'clasesPorAlumno' => $clasesPorAlumno,
                    'usuario' => $usuario,
                    'actividades' => $actividades,
                    'actividadesC' => $actividadesC,
                    'alumno' => $alumno,
                    'alumnos' => $alumnos,
                    'clasesA' => $clasesA,
                    //'calificacionesAlumno' => $calificacionesAlumno,
                ]);
            } else {
                return redirect()->route('tutor.calificaciones')->with(['message' => "No tiene acceso a la clase que intenta acceder", "color" => "red"]);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

}