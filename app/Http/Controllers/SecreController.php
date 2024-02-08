<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\avisos;
use App\Models\alumnos;
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
use App\Models\grado_grupo_alumno;
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

class SecreController extends Controller{

    public function generarContraseña()
    {
        // Generar una parte de la contraseña sin dígitos
        $parteAlfanumerica = Str::random(5, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        // Generar exactamente 3 dígitos
        $parteNumerica = '';
        for ($i = 0; $i < 3; $i++) {
            $parteNumerica .= mt_rand(0, 9);
        }

        // Mezclar las partes de la contraseña para asegurar aleatoriedad
        $password = str_shuffle($parteAlfanumerica . $parteNumerica);
        return $password;
    }

    public function quitarAcentos($palabra)
    {
        $textoConAcentos = $palabra;
        // Remplazar caracteres con tilde
        $textoSinAcentos = strtr($textoConAcentos, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U']);
        return $textoSinAcentos;
    }

    public function obtenerInfoUsuario()
    {
        $idUsuario = auth()->user()->idUsuario;
        $usuario = usuarios::where('idUsuario', $idUsuario)->with(['personal'])->first();
        $usuario->tipoUsuario3 = $usuario->tipoUsuarios->tipoUsuario;
        $usuario->secretariaNombre = $usuario->personal->nombre_completo;

        return $usuario;
    }

    public function inicio()
    {
        $usuario = $this->obtenerInfoUsuario();
        $message = '';
        $color = '';

        $now = Carbon::now();

        // Consulta los avisos que cumplan con las condiciones
        $avisos = avisos::where('fechaHoraInicio', '<=', $now)
            ->where('fechaHoraFin', '>=', $now)
            ->get();

        if ($usuario->cambioContrasenia === 0) {
            $fechaLimite = Carbon::parse($usuario->fecha_Creacion)->addHours(48);
            $fechaFormateada = $fechaLimite->format('d/m/Y');
            $horaFormateada = $fechaLimite->format('H:i');
            $message = "Tiene hasta el " . $fechaFormateada . " a las " . $horaFormateada . " hrs para realizar el cambio de contraseña, en caso contrario, esta se desactivara y sera necesario acudir a la dirección para solucionar la situación";
            $color = "red";
            return Inertia::render('Secre/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color, 'avisos' => $avisos]);
        }


        return Inertia::render('Secre/Inicio', [
            'usuario' => $usuario, 'message' => $message, 'color' => $color, 'avisos' => $avisos
        ]);
    }

    public function perfil()
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $personal = personal::where('idUsuario', $usuario->idUsuario)->with(['generos', 'tipo_sangre', 'direcciones'])->first();

            $personal->domicilio = $personal->direcciones->calle . " #" . $personal->direcciones->numero . ", " . $personal->direcciones->asentamientos->asentamiento
                . ", " . $personal->direcciones->asentamientos->municipios->municipio . ", " . $personal->direcciones->asentamientos->municipios->estados->estado
                . ", " . $personal->direcciones->asentamientos->codigoPostal->codigoPostal;


            return Inertia::render('Secre/Perfil', ['usuario' => $usuario, 'directivo' => $personal, 'usuario' => $usuario]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function cuentas()
    {
        $usuarios = usuarios::whereHas('tipoUsuarios', function ($query) {
        $query->whereIn('tipoUsuario', ['profesor','estudiante','tutor']);//Para ver cuentas solo de esos tipos de usuarios
        })
        ->get();

        return Inertia::render('Secre/Cuentas', [
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
        return redirect()->route('secre.cuentas')->with('message', "Usuario agregado correctamente: " . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||");
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
            return redirect()->route('secre.usuarios')->with('message', "Usuarios eliminados correctamente");
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
        return redirect()->route('secre.cuentas')->with('message', "Usuario eliminado correctamente");
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
        return redirect()->route('secre.cuentas')->with('message', "Usuario actualizado correctamente: " . $usuarios->usuario);;
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
                'alumno' => 'required|array',
            ]);
            $clase_id = $request->clase;
            $alumnos = $request->alumno;
            // Utilizar transacción
            DB::beginTransaction();
            try {
                // Verificar si ya existe una entrada con el mismo idClase e idAlumno
                foreach ($alumnos as $alumno_id) {
                    $existingEntry = clases_alumnos::where('idClase', $clase_id)->where('idAlumno', $alumno_id)->first();
    
                    if (!$existingEntry) {
                        $clase_alumno = new clases_alumnos();
                        $clase_alumno->idClase = $clase_id;
                        $clase_alumno->idAlumno = $alumno_id;
                        $clase_alumno->calificacionClase = 0;
                        $clase_alumno->save();
                    } else {
                        // Si al menos un alumno ya existe, hacer rollback y redirigir
                        DB::rollBack();
                        return redirect()->route('secre.alumnosclases')->with('message', "El alumno ya está agregado en la clase seleccionada");
                    }
                }
                // Commit solo si no hubo problemas
                DB::commit();
    
                return redirect()->route('secre.alumnosclases')->with('message', "Alumno(s) agregado(s) correctamente");
            } catch (\Exception $e) {
                // Manejar excepciones específicas
                DB::rollBack();
                return redirect()->route('secre.alumnosclases')->with('message', "Error al agregar alumnos: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Manejar excepciones generales
            dd($e);
        }
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

                return redirect()->route('secre.perfil')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('secre.perfil')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('secre.perfil')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }
    
    public function avisos()
    {
        if (Auth::check()) {
            try {
                $usuario = $this->obtenerInfoUsuario();
                $avisos = avisos::all();
                $avisosM = $avisos->map(function ($aviso) {
                    $usuarioAviso = usuarios::where('idUsuario', $aviso->idUsuario)->with(['personal'])->first();
                    $aviso->fecha_ini = Carbon::parse($aviso->fechaHoraInicio)->format('d/m/Y H:i');
                    $aviso->fecha_fi = Carbon::parse($aviso->fechaHoraFin)->format('d/m/Y H:i');
                    $aviso->nombre = $usuarioAviso->personal->nombre_completo;
                    return $aviso;
                });
                //dd($usuario -> personalNombre);
                return Inertia::render('Secre/Avisos', [
                    'avisos' => $avisosM,
                    'usuario' => $usuario
                ]);
            } catch (Exception $e) {
            }
        }
    }

    public function agregarAviso(Request $request)
    {
        if (Auth::check()) {
            try {
                $request->validate([
                    'titulo' => 'required',
                    'descripcion' => 'required',
                    'fechaHoraInicio' => 'required',
                    'fechaHoraFin' => 'required',
                ]);
                $usuario = $this->obtenerInfoUsuario();

                $aviso = new avisos();
                $aviso->titulo = $request->titulo;
                $aviso->descripcion = $request->descripcion;
                $aviso->fechaHoraInicio = $request->fechaHoraInicio;
                $aviso->fechaHoraFin = $request->fechaHoraFin;
                $aviso->idUsuario = $usuario->idUsuario;
                $aviso->save();

                return redirect()->route('secre.avisos')->With(["message" => "El aviso se ha creado correctamente", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('secre.avisos')->With(["message" => "Hubó un error al agregar el nuevo aviso", "color" => "red"]);
            }
        }
    }

    public function eliminarAviso($idAviso)
    {
        if (Auth::check()) {
            try {
                $aviso = avisos::find($idAviso);
                if ($aviso) {
                    $aviso->delete();
                    return redirect()->route('secre.avisos')->With(["message" => "El aviso se ha eliminado correctamente", "color" => "green"]);
                }
                return redirect()->route('secre.avisos')->With(["message" => "No se encuentra el aviso que se quiere eliminar", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('secre.avisos')->With(["message" => "Error al eliminar el aviso", "color" => "red"]);
            }
        }
    }

    public function actualizarAviso(Request $request){
        if (Auth::check()) {
            try {
                $request->validate([
                    'titulo' => 'required',
                    'descripcion' => 'required',
                    'fechaHoraInicio' => 'required',
                    'fechaHoraFin' => 'required',
                ]);
                $usuario = $this->obtenerInfoUsuario();

                $aviso = avisos::find($request->idAviso);
                $aviso->titulo = $request->titulo;
                $aviso->descripcion = $request->descripcion;
                $aviso->fechaHoraInicio = $request->fechaHoraInicio;
                $aviso->fechaHoraFin = $request->fechaHoraFin;
                $aviso->idUsuario = $usuario->idUsuario;
                $aviso->save();

                return redirect()->route('secre.avisos')->With(["message" => "El aviso se ha actualizado correctamente", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('secre.avisos')->With(["message" => "Hubó un error al actualizar el nuevo aviso", "color" => "red"]);
            }
        }
    }

    public function eliminarAvisos($avisosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $avisosIdsArray = explode(',', $avisosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $avisosIdsArray = array_map('intval', $avisosIdsArray);
            // Elimina las materias
            avisos::whereIn('idAviso', $avisosIdsArray)->delete();
            
            // Redirige a la página deseada después de la eliminación
            return redirect()->route('secre.avisos')->with(['message' => "Avisos eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            // Manejo de errores
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function tutores_alumnos()
    {
        // Obtención de datos de tutores
        $tutoresPrincipal = tutores::with(['generos', 'direcciones'])->get();

        $tutores = $tutoresPrincipal->map(function ($tutor) {
            $genero = $tutor->generos ? $tutor->generos->genero : null;
            $calle = $tutor->direcciones ? $tutor->direcciones->calle : null;
            $numero = $tutor->direcciones ? $tutor->direcciones->numero : null;

            $tutor->genero = $genero;
            $tutor->calle = $calle;
            $tutor->numero = $numero;
            $tutor->codigoPos = $tutor->direcciones->asentamientos->codigoPostal->codigoPostal;
            $tutor->domicilio = $tutor->direcciones->calle . " #" . $tutor->direcciones->numero . ", " . $tutor->direcciones->asentamientos->asentamiento
                . ", " . $tutor->direcciones->asentamientos->municipios->municipio . ", " . $tutor->direcciones->asentamientos->municipios->estados->estado
                . ", " . $tutor->direcciones->asentamientos->codigoPostal->codigoPostal;
            $tutor->idEstado = $tutor->direcciones->asentamientos->municipios->estados->idEstado;
            $tutor->idMunicipio = $tutor->direcciones->asentamientos->municipios->idMunicipio;
            $tutor->idAsentamiento = $tutor->direcciones->asentamientos->idAsentamiento;

            return $tutor;
        });
        $generos = generos::all();

        // Obtención de datos de alumnos
        $alumnosPrincipal = alumnos::with(['generos', 'direcciones', 'tipo_sangre', 'grados', 'grupos', 'materias', 'tutores'])->get();

        $alumnos = $alumnosPrincipal->map(function ($alumno) {
            $genero = $alumno->generos ? $alumno->generos->genero : null;
            $calle = $alumno->direcciones ? $alumno->direcciones->calle : null;
            $numero = $alumno->direcciones ? $alumno->direcciones->numero : null;

            $alumno->genero = $genero;
            $alumno->calle = $calle;
            $alumno->numero = $numero;
            $alumno->codigoPos = $alumno->direcciones->asentamientos->codigoPostal->codigoPostal;
            $alumno->domicilio = $alumno->direcciones->calle . " #" . $alumno->direcciones->numero . ", " . $alumno->direcciones->asentamientos->asentamiento
                . ", " . $alumno->direcciones->asentamientos->municipios->municipio . ", " . $alumno->direcciones->asentamientos->municipios->estados->estado
                . ", " . $alumno->direcciones->asentamientos->codigoPostal->codigoPostal;
            $alumno->idEstado = $alumno->direcciones->asentamientos->municipios->estados->idEstado;
            $alumno->idMunicipio = $alumno->direcciones->asentamientos->municipios->idMunicipio;
            $alumno->idAsentamiento = $alumno->direcciones->asentamientos->idAsentamiento;

            $grado_grupo_alumno = grado_grupo_alumno::where('idAlumno', $alumno->idAlumno)
                ->where('estatus', '1')
                ->first();
            $alumno->idGradGrupAl = $grado_grupo_alumno->idGradGrupAl;
            $alumno->grado = $grado_grupo_alumno->grados->grado;
            $alumno->grupo = $grado_grupo_alumno->grupos->grupo;

            $alumno->idGrado = $grado_grupo_alumno->idGrado;
            $alumno->idGrupo = $grado_grupo_alumno->idGrupo;
            $alumno->idCiclo = $grado_grupo_alumno->idCiclo;

            if ($alumno->materias != null) {
                $alumno->materia = $alumno->materias->materia;
            } else {
                $alumno->materia = "Ninguno";
            }
            $alumno->tutor = $alumno->tutores->nombre_completo;
            $alumno->tutorTel = $alumno->tutores->numTelefono;
            $alumno->tipoS = $alumno->tipo_Sangre->tipoSangre;
            $alumno->tutorC = $alumno->tutores;
            //$alumno->gradoC = $alumno->grados;
            //$alumno->grados->descripcion = $alumno->grados->grado . " - " . $alumno->grados->ciclos->descripcionCiclo;
            return $alumno;
        });

        $tipo_sangre = tipo_Sangre::all();

        //$gradosPrincipal = grados::with('ciclos')->get();
        /*
        $grados = $gradosPrincipal->map(function ($grado) {
            $grado->descripcion = $grado->grado . " - " . $grado->ciclos->descripcionCiclo;

            return $grado;
        });
        */
        $grados = grados::all();
        $grupos = grupos::all();
        $ciclos = ciclos::all();

        $usuario = $this->obtenerInfoUsuario();
        //$grupos = grupos::all();
        $materiasT = materias::where('esTaller', '1')->get();
        //$materias = materias::where('esTaller', 'true')->get();
        //$alumnos = alumnos::all();
        return Inertia::render('Secre/Tutores_Alumnos', [
            'tutores' => $tutores,
            'alumnos' => $alumnos,
            'generos' => $generos,
            'tipoSangre' => $tipo_sangre,
            'grados' => $grados,
            'grupos' => $grupos,
            'talleres' => $materiasT,
            'usuario' => $usuario,
            'ciclos' => $ciclos,
        ]);
    }

    public function addTutores(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellidoP' => 'required',
                'apellidoM' => 'required',
                'numTelefono' => 'required',
                'correoElectronico' => 'required',
                'genero' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);
            // Validación de Nombre y Apellidos
            $existingTutor = tutores::where([
                ['nombre', $request->nombre],
                ['apellidoP', $request->apellidoP],
                ['apellidoM', $request->apellidoM],
                ['numTelefono', $request->numTelefono],
                ['correoElectronico', $request->correoElectronico],
            ])->exists();

            $existingAddress = direcciones::where([
                ['calle', $request->calle],
                ['numero', $request->numero],
                ['idAsentamiento', $request->asentamiento],
            ])->exists();

            if ($existingTutor || $existingAddress) {
                return redirect()->route('secre.tutoresAlum')->with(["message" => "El tutor ya está registrado o la dirección ya existe.", "color" => "red"]);
            }
            //Contraseña generada
            $contrasenia = $this->generarContraseña();
            //Creacion de usuario
            $usuario = new usuarios();
            $usuario->usuario = strtolower(substr($this->quitarAcentos($request->apellidoP), 0, 2) . substr($this->quitarAcentos($request->apellidoM), 0, 1) . substr($this->quitarAcentos($request->nombre), 0, 1) . substr($this->quitarAcentos($request->correoElectronico), 0, 2) . Str::random(3));
            $usuario->contrasenia = $contrasenia;
            $usuario->password = bcrypt($contrasenia);
            $tipoUsuarioT = tipoUsuarios::where('tipoUsuario', 'tutor')->first();
            $usuario->idTipoUsuario = $tipoUsuarioT->idTipoUsuario;
            $usuario->save();

            //Se busca el tipo de usuario en la BD
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'tutor')->first();

            $usuarioTipoUsuario = new usuarios_tiposUsuarios();
            $usuarioTipoUsuario->idUsuario = $usuario->idUsuario;
            $usuarioTipoUsuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuarioTipoUsuario->save();

            $direccion = new direcciones();
            $direccion->calle = $request->calle;
            $direccion->numero = $request->numero;
            $direccion->idAsentamiento = $request->asentamiento;
            $direccion->save();

            $tutor = new tutores();
            $tutor->nombre = $request->nombre;
            $tutor->apellidoP = $request->apellidoP;
            $tutor->apellidoM = $request->apellidoM;
            $tutor->idUsuario = $usuario->idUsuario;
            $tutor->numTelefono = $request->numTelefono;
            $tutor->idDireccion = $direccion->idDireccion;
            $tutor->correoElectronico = $request->correoElectronico;
            $tutor->idGenero = $request->genero;
            //columna nombre completo
            $nombreCompleto = $tutor->nombre . ' ' . $tutor->apellidoP . ' ' . $tutor->apellidoM;
            $tutor->nombre_completo = $nombreCompleto;

            //Guardado
            $tutor->save();
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Tutor agregado correctamente: " . $tutor->nombre . " " . $tutor->apellidoP . " " . $tutor->apellidoM . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('secre.tutoresAlum')->With(["message" => "El tutor no se agrego correctamente", "color" => "red"]);
        }
    }

    public function actualizarTutor(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellidoP' => 'required',
                'apellidoM' => 'required',
                'numTelefono' => 'required',
                'correoElectronico' => 'required',
                'genero' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);

            $direccion = direcciones::find($request->idDomicilio);
            $direccion->calle = $request->calle;
            $direccion->numero = $request->numero;
            $direccion->idAsentamiento = $request->asentamiento;
            $direccion->save();

            $tutor = tutores::find($request->idTutor);
            $tutor->nombre = $request->nombre;
            $tutor->apellidoP = $request->apellidoP;
            $tutor->apellidoM = $request->apellidoM;
            $tutor->numTelefono = $request->numTelefono;
            $tutor->idDireccion = $direccion->idDireccion;
            $tutor->correoElectronico = $request->correoElectronico;
            $tutor->idGenero = $request->genero;
            //columna nombre completo
            $nombreCompleto = $tutor->nombre . ' ' . $tutor->apellidoP . ' ' . $tutor->apellidoM;
            $tutor->nombre_completo = $nombreCompleto;

            //Guardado
            $tutor->save();
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Tutor actualizado correctamente: " . $tutor->nombre . " " . $tutor->apellidoP . " " . $tutor->apellidoM, "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "El tutor no se actualizo correctamente", "color" => "red"]);
            dd($e);
        }
    }

    public function eliminarTutor($idTutor)
    {
        try {
            //Se busca el tipo de usuario en la BD
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'tutor')->first();

            $tutor = tutores::find($idTutor);
            $usuario = usuarios::find($tutor->idUsuario);
            $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                ->first();
            $direccion = direcciones::find($tutor->idDireccion);
            $tutor->delete();
            $usuarioTipoUsuario->delete();
            $usuario->delete();
            $direccion->delete();

            return redirect()->route('secre.tutoresAlum')->with(['message' => "Tutor eliminado correctamente", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al eliminar al tutor: Primero debe eliminar a los tutorados" . $e, "color" => "red"]);
        }
    }

    public function elimTutores($tutoresIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $tutoresIdsArray = explode(',', $tutoresIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $tutoresIdsArray = array_map('intval', $tutoresIdsArray);

            //Se busca el tipo de usuario en la BD
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'tutor')->first();

            for ($i = 0; $i < count($tutoresIdsArray); $i++) {
                $tutor = tutores::find($tutoresIdsArray[$i]);
                $usuario = usuarios::find($tutor->idUsuario);
                $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                    ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                    ->first();
                $direccion = direcciones::find($tutor->idDireccion);
                $tutor->delete();
                $usuarioTipoUsuario->delete();
                $usuario->delete();
                $direccion->delete();
            }
            return redirect()->route('secre.tutoresAlum')->with(['message' => "Tutores eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al eliminar a los tutor: Primero debe eliminar a los tutorados " . $e, "color" => "red"]);
            // Manejo de errores            
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function buscarTutor(Request $request)
    {
        $query = $request->input('query');

        // Realiza la búsqueda full-text en la columna "nombre_completo"
        $tutores = tutores::where('nombre_completo', 'LIKE', '%' . $query . '%')
            ->get();
        /*$tutores = tutores::whereRaw('MATCH(nombre_completo) AGAINST(? IN BOOLEAN MODE)', [$query])
            ->get();
        */
        // Formatea los resultados para enviarlos como respuesta JSON
        $results = [];
        foreach ($tutores as $tutor) {
            $results[] = [
                'idTutor' => $tutor->idTutor, // Corrige esto para usar el campo idTutor
                'nombre_completo' => $tutor->nombre_completo // El nombre completo del tutor
            ];
        }

        return response()->json($results);
    }

    public function agregarAlumno(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellidoP' => 'required',
                'apellidoM' => 'required',
                'numTelefono' => 'required',
                'correoElectronico' => 'required',
                'genero' => 'required',
                'fechaNacimiento' => 'required',
                'genero' => 'required',
                'curp' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);
            $existingAlumno = alumnos::where([
                ['nombre', $request->nombre],
                ['apellidoP', $request->apellidoP],
                ['apellidoM', $request->apellidoM],
                ['numTelefono', $request->numTelefono],
                ['correoElectronico', $request->correoElectronico],
                ['curp', $request->curp],
                ['fechaNacimiento', $request->fechaNacimiento]
            ])->exists();

            if ($existingAlumno) {
                return redirect()->route('secre.tutoresAlum')->with(["message" => "El alumno ya está registrado.", "color" => "red"]);
            }
            //fechaFormateada
            $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
            //Contraseña generada
            $contrasenia = $this->generarContraseña();
            //Creacion de usuario
            $usuario = new usuarios();
            $usuario->usuario = strtolower(substr($this->quitarAcentos($request->apellidoP), 0, 2) . substr($this->quitarAcentos($request->apellidoM), 0, 1) . substr($this->quitarAcentos($request->nombre), 0, 1) . $fechaFormateada . Str::random(3));
            $usuario->contrasenia = $contrasenia; //Hash::make($contrasenia);
            $usuario->password =  bcrypt($contrasenia);
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'estudiante')->first();
            $usuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuario->save();

            //Se busca el tipo de usuario en la BD
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'estudiante')->first();

            $usuarioTipoUsuario = new usuarios_tiposUsuarios();
            $usuarioTipoUsuario->idUsuario = $usuario->idUsuario;
            $usuarioTipoUsuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuarioTipoUsuario->save();

            //Se guarda el domicilio
            $domicilio = new direcciones();
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            $alumno = new alumnos();
            $alumno->nombre = $request->nombre;
            $alumno->apellidoP = $request->apellidoP;
            $alumno->apellidoM = $request->apellidoM;
            $alumno->fechaNacimiento = $request->fechaNacimiento;
            $alumno->CURP = $request->curp;
            $alumno->idGenero = $request->genero;
            $alumno->correoElectronico = $request->correoElectronico;
            $alumno->numTelefono = $request->numTelefono;
            $alumno->idTipoSangre = $request->tipoSangre;
            $alumno->alergias = $request->alergias;
            $alumno->discapacidad = $request->discapacidad;
            $alumno->idDireccion = $domicilio->idDireccion;
            $alumno->esForaneo = $request->foraneo;
            /*
            $alumno->idGrado = $request->grado["idGrado"];
            $alumno->idGrupo = $request->grupo;
            */
            $alumno->idMateria = $request->taller["idMateria"];
            $alumno->idTutor = $request->tutor["idTutor"];
            $alumno->idUsuario = $usuario->idUsuario;

            $nombreCompleto = $alumno->nombre . ' ' . $alumno->apellidoP . ' ' . $alumno->apellidoM;
            $alumno->nombre_completo = $nombreCompleto;

            if ($alumno->alergias == null) {
                $alumno->alergias = "Ninguna";
            }

            if ($alumno->discapacidad == null) {
                $alumno->discapacidad = "Ninguna";
            }

            $alumno->save();

            $grado_grupo_alumno = new grado_grupo_alumno();
            $grado_grupo_alumno->idAlumno = $alumno->idAlumno;
            $grado_grupo_alumno->idGrado = $request->grado;
            $grado_grupo_alumno->idGrupo = $request->grupo;
            $grado_grupo_alumno->idCiclo = $request->ciclos;
            $grado_grupo_alumno->save();
            return redirect()->route('secre.tutoresAlum')->with(['message' => "Alumno agregado correctamente: " . $nombreCompleto . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||.", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al agregar al alumno.", "color" => "red"]);
        }
    }

    public function actualizarAlumno(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellidoP' => 'required',
                'apellidoM' => 'required',
                'numTelefono' => 'required',
                'correoElectronico' => 'required',
                'genero' => 'required',
                'fechaNacimiento' => 'required',
                'genero' => 'required',
                'curp' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);
            $conflictingAlumno = alumnos::where('idAlumno', '!=', $request->idAlumno) // Excluir el propio periodo que se está actualizando
                ->where(function ($query) use ($request) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->where('nombre', $request->nombre)
                            ->where('apellidoM', $request->apellidoM)
                            ->where('apellidoP', $request->apellidoP)
                            ->where('apellidoP', $request->apellidoP)
                            ->where('numTelefono', $request->numTelefono)
                            ->where('correoElectronico', $request->correoElectronico)
                            ->where('idGenero', $request->genero)
                            ->where('CURP', $request->curp)
                            ->where('fechaNacimiento', $request->fechaNacimiento);
                    });
                })
                ->first();

            if ($conflictingAlumno) {
                return redirect()->route('secre.tutoresAlum')->with(['message' => "Los datos del alumno ya existen", "color" => "yellow"]);
            }

            $alumno = alumnos::find($request->idAlumno);
            $domicilio = direcciones::find($alumno->idDireccion);
            $grado_grupo_alumno = grado_grupo_alumno::find($request->idGradGrupAl);
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            $alumno->nombre = $request->nombre;
            $alumno->apellidoP = $request->apellidoP;
            $alumno->apellidoM = $request->apellidoM;
            $alumno->fechaNacimiento = $request->fechaNacimiento;
            $alumno->CURP = $request->curp;
            $alumno->idGenero = $request->genero;
            $alumno->correoElectronico = $request->correoElectronico;
            $alumno->numTelefono = $request->numTelefono;
            $alumno->idTipoSangre = $request->tipoSangre;
            $alumno->alergias = $request->alergias;
            $alumno->discapacidad = $request->discapacidad;
            $alumno->idDireccion = $domicilio->idDireccion;
            $alumno->esForaneo = $request->foraneo;
            $grado_grupo_alumno->idGrado = $request->grado;
            $grado_grupo_alumno->idGrupo = $request->grupo;
            $grado_grupo_alumno->idCiclo = $request->ciclos;
            /*
            $alumno->idGrado = $request->grado["idGrado"];
            $alumno->idGrupo = $request->grupo;
            */
            $alumno->idMateria = $request->taller["idMateria"];
            $alumno->idTutor = $request->tutor["idTutor"];

            $nombreCompleto = $alumno->nombre . ' ' . $alumno->apellidoP . ' ' . $alumno->apellidoM;
            $alumno->nombre_completo = $nombreCompleto;

            if ($alumno->alergias == null) {
                $alumno->alergias = "Ninguna";
            }

            if ($alumno->discapacidad == null) {
                $alumno->discapacidad = "Ninguna";
            }

            $alumno->save();
            $grado_grupo_alumno->save();
            return redirect()->route('secre.tutoresAlum')->with(['message' => "Alumno actualizado correctamente: " . $nombreCompleto . ".", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al actualizar la informacion del alumno.", "color" => "red"]);
        }
    }

    public function eliminarAlumno($idAlumno)
    {
        try {
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'estudiante')->first();
            $alumno = alumnos::find($idAlumno);
            $grado_grupo_alumno = grado_grupo_alumno::where('idAlumno', $alumno->idAlumno)
                ->where('estatus', '1')
                ->first();
            //Alumnos y clases
            $calif_Per = calificaciones_periodos::where('idAlumno', $alumno->idAlumno)->get();
            $calificaciones = calificaciones::where('idAlumno', $alumno->idAlumno)->get();
            $cl_alumnos = clases_alumnos::where('idAlumno', $alumno->idAlumno)->get();

            if (!$calif_Per->isEmpty()) {
                foreach ($calif_Per as $c_per) {
                    $c_per->delete();
                }
            }
            if (!$calificaciones->isEmpty()) {
                foreach ($calificaciones as $cali) {
                    $cali->delete();
                }
            }
            if (!$cl_alumnos->isEmpty()) {
                foreach ($cl_alumnos as $cl_a) {
                    $cl_a->delete();
                }
            }

            $usuario = usuarios::find($alumno->idUsuario);
            $direccion = direcciones::find($alumno->idDireccion);
            $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                ->first();
            $grado_grupo_alumno->delete();
            $alumno->delete();
            $usuarioTipoUsuario->delete();
            $usuario->delete();
            $direccion->delete();

            return redirect()->route('secre.tutoresAlum')->with(['message' => "Alumno eliminado correctamente.", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al eliminar al alumno. ", "color" => "red"]);
        }
    }

    public function eliminarAlumnos($alumnosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $alumnosIdsArray = explode(',', $alumnosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $alumnosIdsArray = array_map('intval', $alumnosIdsArray);

            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'estudiante')->first();

            for ($i = 0; $i < count($alumnosIdsArray); $i++) {
                $alumno = alumnos::find($alumnosIdsArray[$i]);
                //Alumnos y clases
                $calif_Per = calificaciones_periodos::where('idAlumno', $alumno->idAlumno)->get();
                $calificaciones = calificaciones::where('idAlumno', $alumno->idAlumno)->get();
                $cl_alumnos = clases_alumnos::where('idAlumno', $alumno->idAlumno)->get();
                $grado_grupo_alumno = grado_grupo_alumno::where('idAlumno', $alumno->idAlumno)
                    ->where('estatus', '1')
                    ->first();

                if (!$calif_Per->isEmpty()) {
                    foreach ($calif_Per as $c_per) {
                        $c_per->delete();
                    }
                }
                if (!$calificaciones->isEmpty()) {
                    foreach ($calificaciones as $cali) {
                        $cali->delete();
                    }
                }
                if (!$cl_alumnos->isEmpty()) {
                    foreach ($cl_alumnos as $cl_a) {
                        $cl_a->delete();
                    }
                }
                $usuario = usuarios::find($alumno->idUsuario);
                $direccion = direcciones::find($alumno->idDireccion);
                $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                    ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                    ->first();
                $grado_grupo_alumno->delete();
                $alumno->delete();
                $usuarioTipoUsuario->delete();
                $usuario->delete();
                $direccion->delete();
            }

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('secre.tutoresAlum')->with(['message' => "Alumnos eliminados correctamente.", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('secre.tutoresAlum')->With(["message" => "Error al eliminar a los alumnos.", "color" => "red"]);
        }
    }
}