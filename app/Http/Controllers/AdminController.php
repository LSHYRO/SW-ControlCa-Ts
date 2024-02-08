<?php

namespace App\Http\Controllers;

use App\Models\personas;
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
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
use Mockery\Undefined;

class AdminController extends Controller
{
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

    ////////////////////////////////////////////////////////////////////////////////////////////////
    // Funciones para acceder a la página de Inicio    
    public function index()
    {
        return Inertia::render('Principal');
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
            return Inertia::render('Admin/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color]);
        }
        return Inertia::render('Admin/Inicio', ['usuario' => $usuario, 'message' => $message, 'color' => $color]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////
    // Funciones para acceder y funciones de profesor
    //  Renderizado de la página de docentes
    public function profesores()
    {
        $personal = Personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->leftJoin('tipo_sangre', 'personal.idTipoSangre', '=', 'tipo_sangre.idTipoSangre')
            ->leftJoin('direcciones', 'personal.idDireccion', '=', 'direcciones.idDireccion')
            ->where('tipo_personal.tipo_personal', 'Profesor') //Le puse con mayuscula la P
            ->get();

        $tipo_sangre = tipo_Sangre::all();
        $generos = generos::all();
        $direcciones = direcciones::all();

        $personalConNombres = $personal->map(function ($persona) use ($generos, $tipo_sangre, $direcciones) {
            $genero = $generos->where('idGenero', $persona->idGenero)->first();
            $tipo_sangre = $tipo_sangre->where('idTipoSangre', $persona->idTipoSangre)->first();
            $direccion = $direcciones->where('idDireccion', $persona->idDireccion)->first();
            $persona->genero = $genero ? $genero->genero : null;
            $persona->tipoSangre = $tipo_sangre ? $tipo_sangre->tipoSangre : null;
            $persona->direccion = $direccion ? $direccion->calle . " #" . $direccion->numero . ", " . $direccion->asentamientos->asentamiento . ", " . $direccion->asentamientos->municipios->municipio . ", " .  $direccion->asentamientos->municipios->estados->estado . ", " . $direccion->asentamientos->codigoPostal->codigoPostal : null;
            $persona->calle = $direccion ? $direccion->calle : null;
            $persona->numero = $direccion ? $direccion->numero : null;
            $persona->codigoPos = $direccion ? $direccion->asentamientos->codigoPostal->codigoPostal : null;
            $persona->idAsentamiento = $direccion ? $direccion->asentamientos->idAsentamiento : null;
            $persona->idMunicipio = $direccion ? $direccion->asentamientos->municipios->idMunicipio : null;
            $persona->idEstado = $direccion ? $direccion->asentamientos->municipios->estados->idEstado : null;
            return $persona;
        });

        $usuario = $this->obtenerInfoUsuario();
        return Inertia::render('Admin/Profesores', [
            'personal' => $personalConNombres, 'tipoSangre' => $tipo_sangre, 'generos' => $generos, 'usuario' => $usuario
        ]);
    }

    //  Función para agregar profesores y redireccionar a la página de profesores
    public function addProfesores(Request $request)
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
                'rfc' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);
            //fechaFormateada
            $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
            //Contraseña generada
            $contrasenia = $this->generarContraseña();
            //Creacion de usuario
            $usuario = new usuarios();
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();
            $usuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuario->usuario = strtolower(substr($this->quitarAcentos($request->apellidoP), 0, 2) . substr($this->quitarAcentos($request->apellidoM), 0, 1) . substr($this->quitarAcentos($request->nombre), 0, 1) . $fechaFormateada . Str::random(3));
            $usuario->contrasenia = $contrasenia;
            $usuario->password = bcrypt($contrasenia);
            $usuario->save();

            //Se busca el tipo de usuario en la BD
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first(); //Le puse P mayuscula

            $usuarioTipoUsuario = new usuarios_tiposUsuarios();
            $usuarioTipoUsuario->idUsuario = $usuario->idUsuario;
            $usuarioTipoUsuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuarioTipoUsuario->save();

            //Se guarda el domicilio del profesor
            $domicilio = new direcciones();
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            //Se busca el tipo de personal en la BD
            $tipo_personal = tipo_personal::where('tipo_personal', 'Profesor')->first(); //Le puse P mayuscula

            //$personal = new personal($request->input());
            $personal = new personal();
            $personal->apellidoP = $request->apellidoP;
            $personal->apellidoM = $request->apellidoM;
            $personal->nombre = $request->nombre;
            $personal->correoElectronico = $request->correoElectronico;
            $personal->numTelefono = $request->numTelefono;
            $personal->idGenero = $request->genero;
            $personal->fechaNacimiento = $request->fechaNacimiento;
            $personal->CURP = $request->curp;
            $personal->rfc = $request->rfc;
            $personal->idTipoSangre = $request->tipoSangre;
            $personal->alergias = $request->alergias;
            $personal->discapacidad = $request->discapacidad;
            $personal->idDireccion = $domicilio->idDireccion;
            $personal->idUsuario = $usuario->idUsuario;
            $personal->id_tipo_personal = $tipo_personal->id_tipo_personal;
            //$personal->activo = 1;

            $existingPersonal = personal::where([
                ['nombre', $request->nombre],
                ['apellidoP', $request->apellidoP],
                ['apellidoM', $request->apellidoM],
                ['numTelefono', $request->numTelefono],
                ['correoElectronico', $request->correoElectronico],
                ['curp', $request->curp],
                ['rfc', $request->rfc],
                ['id_tipo_personal', $tipo_personal->id_tipo_personal], // Asegura que solo verifique profesores
            ])->exists();
            
            if ($existingPersonal ) {
                return redirect()->route('admin.profesores')->with(["message" => "El profesor ya está registrado", "color" => "red"]);
            }

            //columna nombre completo
            $nombreCompleto = $personal->nombre . ' ' . $personal->apellidoP . ' ' . $personal->apellidoM;
            $personal->nombre_completo = $nombreCompleto;

            if ($personal->alergias == null) {
                $personal->alergias = "Ninguna";
            }

            if ($personal->discapacidad == null) {
                $personal->discapacidad = "Ninguna";
            }

            //Guardado
            $personal->save();
            return redirect()->route('admin.profesores')->With(["message" => "Profesor agregado correctamente: " . $personal->nombre . " " . $personal->apellidoP . " " . $personal->apellidoM . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||.", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route("admin.profesores")->with(["message" => "Error al agregar al profesor.", "color" => "red"]);
        }
    }

    //  Función para eliminar un profesor y redireccionar a la página de profesores o docentes
    public function eliminarProfesores($idPersonal)
    {
        try {
            $clasesProfesor = clases::where('idPersonal', $idPersonal)->get();
            if (!$clasesProfesor->isEmpty()) {
                //Se eliminan calificacionesPeriodos, calificaciones y clases alumnos para al final eliminar las clases del profesor
                foreach ($clasesProfesor as $clase) {
                    $calificacionesPeriodos = calificaciones_periodos::where('idClase', $clase->idClase)->get();
                    $calificaciones = calificaciones::where('idClase', $clase->idClase)->get();
                    $clases_alumnos = clases_alumnos::where('idClase', $clase->idClase)->get();

                    if (!$calificacionesPeriodos->isEmpty()) {
                        foreach ($calificacionesPeriodos as $calPer) {
                            $calPer->delete();
                        }
                    }
                    if (!$calificaciones->isEmpty()) {
                        foreach ($calificaciones as $calificacion) {
                            $calificacion->delete();
                        }
                    }
                    if (!$clases_alumnos->isEmpty()) {
                        foreach ($clases_alumnos as $clasAlu) {
                            $clasAlu->delete();
                        }
                    }
                    $clase->delete();
                }
            }
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first(); //Le puse P mayuscula
            $personal = personal::find($idPersonal);
            $usuario = usuarios::find($personal->idUsuario);
            $direccion = direcciones::find($personal->idDireccion);
            $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                ->first();
            $personal->delete();
            $usuarioTipoUsuario->delete();
            $usuario->delete();
            $direccion->delete();

            return redirect()->route('admin.profesores')->With(["message" => "Profesor eliminado correctamente", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.profesores')->with(["message" => "Error al eliminar los datos del profesor", "color" => "red"]);
        }
    }

    //  Función para eliminar varios profesores a la vez y redireccionar a la página de profesores o docentes    
    public function elimprofesores($personalIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $personalIdsArray = explode(',', $personalIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $personalIdsArray = array_map('intval', $personalIdsArray);

            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first(); //Le puse P mayuscula

            for ($i = 0; $i < count($personalIdsArray); $i++) {
                $personal = personal::find($personalIdsArray[$i]);
                $clasesProfesor = clases::where('idPersonal', $personal->idPersonal)->get();
                if (!$clasesProfesor->isEmpty()) {
                    //Se eliminan calificacionesPeriodos, calificaciones y clases alumnos para al final eliminar las clases del profesor
                    foreach ($clasesProfesor as $clase) {
                        $calificacionesPeriodos = calificaciones_periodos::where('idClase', $clase->idClase)->get();
                        $calificaciones = calificaciones::where('idClase', $clase->idClase)->get();
                        $clases_alumnos = clases_alumnos::where('idClase', $clase->idClase)->get();

                        if (!$calificacionesPeriodos->isEmpty()) {
                            foreach ($calificacionesPeriodos as $calPer) {
                                $calPer->delete();
                            }
                        }
                        if (!$calificaciones->isEmpty()) {
                            foreach ($calificaciones as $calificacion) {
                                $calificacion->delete();
                            }
                        }
                        if (!$clases_alumnos->isEmpty()) {
                            foreach ($clases_alumnos as $clasAlu) {
                                $clasAlu->delete();
                            }
                        }
                        $clase->delete();
                    }
                }


                $usuario = usuarios::find($personal->idUsuario);
                $direccion = direcciones::find($personal->idDireccion);
                $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                    ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                    ->first();
                $personal->delete();
                $usuarioTipoUsuario->delete();
                $usuario->delete();
                $direccion->delete();
            }
            return redirect()->route('admin.profesores')->With(["message" => "Profesores eliminados correctamente.", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            //return redirect()->route('admin.profesores')->With(["message" => "Error al eliminar los datos de los profesores.", "color" => "red"]);
        }
    }

    //  Función para actualizar los datos de los profesores y redireccionar a la página de profesores o docentes
    public function actualizarProfesor(Request $request, $idPersonal)
    {
        try {
            $personal = personal::find($idPersonal);
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
                'rfc' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);

            //$personal->fill($request->input())->saveOrFail();
            //Se guarda el domicilio del profesor
            $domicilio = direcciones::findOrFail($request->idDomicilio);
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            //Se busca el tipo de personal en la BD
            $tipo_personal = tipo_personal::where('tipo_personal', 'Profesor')->first(); //Le puse P mayuscula

            //$personal = new personal($request->input());
            $personal = personal::findOrFail($request->idPersonal);
            $personal->apellidoP = $request->apellidoP;
            $personal->apellidoM = $request->apellidoM;
            $personal->nombre = $request->nombre;
            $personal->correoElectronico = $request->correoElectronico;
            $personal->numTelefono = $request->numTelefono;
            $personal->idGenero = $request->genero;
            $personal->fechaNacimiento = $request->fechaNacimiento;
            $personal->CURP = $request->curp;
            $personal->RFC = $request->rfc;
            $personal->idTipoSangre = $request->tipoSangre;
            $personal->alergias = $request->alergias;
            $personal->discapacidad = $request->discapacidad;
            $personal->idDireccion = $domicilio->idDireccion;
            $personal->id_tipo_personal = $tipo_personal->id_tipo_personal;

            //columna nombre completo
            $nombreCompleto = $personal->nombre . ' ' . $personal->apellidoP . ' ' . $personal->apellidoM;
            $personal->nombre_completo = $nombreCompleto;

            if ($personal->alergias == null) {
                $personal->alergias = "Ninguna";
            }

            if ($personal->discapacidad == null) {
                $personal->discapacidad = "Ninguna";
            }

            //Guardado
            $personal->save();
            return redirect()->route('admin.profesores')->With(["message" => "Informacion del profesor actualizado correctamente: " . $personal->nombre . " " . $personal->apellidoP . " " . $personal->apellidoM . ".", "color" => "green"]);
        } catch (Exception $e) {
            //dd($e);
            return redirect()->route("admin.profesores")->with(["message" => "Error al actualizar los datos del profesor.", "color" => "red"]);
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function directivos()
    {
        $personal = Personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->leftJoin('tipo_sangre', 'personal.idTipoSangre', '=', 'tipo_sangre.idTipoSangre')
            ->leftJoin('direcciones', 'personal.idDireccion', '=', 'direcciones.idDireccion')
            //->where('tipo_personal.tipo_personal', 'profesor')
            ->where(function ($query) {
                $query->where('tipo_personal.tipo_personal', 'Director')
                    ->orWhere('tipo_personal.tipo_personal', 'Personal escolar');
            })
            ->get();

        $tipo_sangre = tipo_Sangre::all();
        $generos = generos::all();
        $direcciones = direcciones::all();
        $tipo_personal = tipo_personal::all();


        $personalConNombres = $personal->map(function ($persona) use ($generos, $tipo_sangre, $direcciones) {
            $genero = $generos->where('idGenero', $persona->idGenero)->first();
            $tipo_sangre = $tipo_sangre->where('idTipoSangre', $persona->idTipoSangre)->first();
            $direccion = $direcciones->where('idDireccion', $persona->idDireccion)->first();
            $persona->genero = $genero ? $genero->genero : null;
            $persona->tipoSangre = $tipo_sangre ? $tipo_sangre->tipoSangre : null;
            $persona->direccion = $direccion ? $direccion->calle . " #" . $direccion->numero . ", " . $direccion->asentamientos->asentamiento . ", " . $direccion->asentamientos->municipios->municipio . ", " .  $direccion->asentamientos->municipios->estados->estado . ", " . $direccion->asentamientos->codigoPostal->codigoPostal : null;
            $persona->calle = $direccion ? $direccion->calle : null;
            $persona->numero = $direccion ? $direccion->numero : null;
            $persona->codigoPos = $direccion ? $direccion->asentamientos->codigoPostal->codigoPostal : null;
            $persona->idAsentamiento = $direccion ? $direccion->asentamientos->idAsentamiento : null;
            $persona->idMunicipio = $direccion ? $direccion->asentamientos->municipios->idMunicipio : null;
            $persona->idEstado = $direccion ? $direccion->asentamientos->municipios->estados->idEstado : null;
            return $persona;
        });

        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/Directivos', [
            'personal' => $personalConNombres,
            'tipoSangre' => $tipo_sangre,
            'generos' => $generos,
            'tipo_personal' => $tipo_personal,
            'usuario' => $usuario
        ]);
    }

    public function addDirectivos(Request $request)
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
                'rfc' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
                'tipoPersonal' => 'required',
            ]);

            //fechaFormateada
            $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
            //Contraseña generada
            $contrasenia = $this->generarContraseña();
            //Creacion de usuario
            $usuario = new usuarios();
            $usuario->usuario = strtolower(substr($this->quitarAcentos($request->apellidoP), 0, 2) . substr($this->quitarAcentos($request->apellidoM), 0, 1) . substr($this->quitarAcentos($request->nombre), 0, 1) . $fechaFormateada . Str::random(3));
            $usuario->contrasenia = $contrasenia;
            $usuario->password = bcrypt($contrasenia);

            $tipo_personal = tipo_personal::find($request->tipoPersonal);

            // Se asigna el tipo de usuario según el tipo_personal seleccionado
            if ($tipo_personal->tipo_personal === 'Director') {
                $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'director')->first();
            } elseif ($tipo_personal->tipo_personal === 'Personal escolar') {
                $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'directivo')->first();
            } else {
                // Manejo de caso no previsto
                // Puedes decidir qué hacer en caso de que el tipo_personal no sea 'Director' ni 'Personal escolar'
            }

            $usuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;

            $usuario->save();

            //Se busca el tipo de usuario en la BD
            //$tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();

            $usuarioTipoUsuario = new usuarios_tiposUsuarios();
            $usuarioTipoUsuario->idUsuario = $usuario->idUsuario;
            $usuarioTipoUsuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
            $usuarioTipoUsuario->save();

            //Se guarda el domicilio del profesor
            $domicilio = new direcciones();
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            //$personal = new personal($request->input());
            $personal = new personal();
            $personal->apellidoP = $request->apellidoP;
            $personal->apellidoM = $request->apellidoM;
            $personal->nombre = $request->nombre;
            $personal->correoElectronico = $request->correoElectronico;
            $personal->numTelefono = $request->numTelefono;
            $personal->idGenero = $request->genero;
            $personal->fechaNacimiento = $request->fechaNacimiento;
            $personal->CURP = $request->curp;
            $personal->rfc = $request->rfc;
            $personal->idTipoSangre = $request->tipoSangre;
            $personal->alergias = $request->alergias;
            $personal->discapacidad = $request->discapacidad;
            $personal->idDireccion = $domicilio->idDireccion;
            $personal->idUsuario = $usuario->idUsuario;
            $personal->id_tipo_personal = $request->tipoPersonal;
            //$personal->activo = 1;
            // Verificar duplicados solo para tipo de personal 'Director' o 'Personal escolar'
        $existingPersonal = personal::where([
            ['nombre', $request->nombre],
            ['apellidoP', $request->apellidoP],
            ['apellidoM', $request->apellidoM],
            ['numTelefono', $request->numTelefono],
            ['correoElectronico', $request->correoElectronico],
            ['curp', $request->curp],
            ['rfc', $request->rfc],
            ['id_tipo_personal', $tipo_personal->id_tipo_personal],
        ])->exists();

        if ($existingPersonal) {
            return redirect()->route('admin.directivos')->with([
                "message" => "El directivo ya está registrado",
                "color" => "red"
            ]);
        }

            //columna nombre completo
            $nombreCompleto = $personal->nombre . ' ' . $personal->apellidoP . ' ' . $personal->apellidoM;
            $personal->nombre_completo = $nombreCompleto;

            if ($personal->alergias == null) {
                $personal->alergias = "Ninguna";
            }

            if ($personal->discapacidad == null) {
                $personal->discapacidad = "Ninguna";
            }

            //Guardado
            $personal->save();
            return redirect()->route('admin.directivos')->With(["message" => "Directivo agregado correctamente: " . $personal->nombre . " " . $personal->apellidoP . " " . $personal->apellidoM . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia , "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function eliminarDirectivos($idPersonal)
    {

        $tipoUsuario = tipoUsuarios::where(function ($query) {
            $query->where('tipoUsuario', 'Director')
                ->orWhere('tipoUsuario', 'Personal escolar');
        })->first(); //cambie get()

        $personal = personal::find($idPersonal);
        $usuario = usuarios::find($personal->idUsuario);
        $direccion = direcciones::find($personal->idDireccion);
        $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
            ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
            ->first();
        $personal->delete();
        $usuario->delete();
        if ($usuarioTipoUsuario) {
            $usuarioTipoUsuario->delete();
        }
        $direccion->delete();
        return redirect()->route('admin.directivos')->With(["message" => "Directivo eliminado correctamente", "color" => "green"]);
    }

    public function elimDirectivos($personalIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $personalIdsArray = explode(',', $personalIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $personalIdsArray = array_map('intval', $personalIdsArray);

            $tipoUsuario = tipoUsuarios::where(function ($query) {
                $query->where('tipoUsuario', 'Director')
                    ->orWhere('tipoUsuario', 'Personal escolar');
            })->first(); //cambie get()

            for ($i = 0; $i < count($personalIdsArray); $i++) {
                $personal = personal::find($personalIdsArray[$i]);
                $direccion = direcciones::find($personal->idDireccion);
                $usuario = usuarios::find($personal->idUsuario);
                $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
                    ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
                    ->first();

                if ($usuarioTipoUsuario) {
                    $usuarioTipoUsuario->delete();
                }
                $personal->delete();
                $usuario->delete();
                //$usuarioTipoUsuario->delete();
                $direccion->delete();
            }
            return redirect()->route('admin.directivos')->With(["message" => "Directivos eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function actualizarDirectivo(Request $request, $idPersonal)
    {
        try {
            $personal = personal::find($idPersonal);
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
                'rfc' => 'required',
                'tipoSangre' => 'required',
                'calle' => 'required',
                'numero' => 'required',
                'asentamiento' => 'required',
            ]);

            //$personal->fill($request->input())->saveOrFail();
            //Se guarda el domicilio del profesor
            $domicilio = direcciones::findOrFail($request->idDomicilio);
            $domicilio->calle = $request->calle;
            $domicilio->numero = $request->numero;
            $domicilio->idAsentamiento = $request->asentamiento;
            $domicilio->save();

            $tipo_personal = tipo_personal::where(function ($query) {
                $query->where('tipo_personal', 'Director')
                    ->orWhere('tipo_personal', 'Personal escolar');
            })->first();

            //$personal = new personal($request->input());
            $personal = personal::findOrFail($request->idPersonal);
            $personal->apellidoP = $request->apellidoP;
            $personal->apellidoM = $request->apellidoM;
            $personal->nombre = $request->nombre;
            $personal->correoElectronico = $request->correoElectronico;
            $personal->numTelefono = $request->numTelefono;
            $personal->idGenero = $request->genero;
            $personal->fechaNacimiento = $request->fechaNacimiento;
            $personal->CURP = $request->curp;
            $personal->RFC = $request->rfc;
            $personal->idTipoSangre = $request->tipoSangre;
            $personal->alergias = $request->alergias;
            $personal->discapacidad = $request->discapacidad;
            $personal->idDireccion = $domicilio->idDireccion;
            $personal->id_tipo_personal = $tipo_personal->id_tipo_personal;

            //columna nombre completo
            $nombreCompleto = $personal->nombre . ' ' . $personal->apellidoP . ' ' . $personal->apellidoM;
            $personal->nombre_completo = $nombreCompleto;

            if ($personal->alergias == null) {
                $personal->alergias = "Ninguna";
            }

            if ($personal->discapacidad == null) {
                $personal->discapacidad = "Ninguna";
            }

            //Guardado
            $personal->save();
            return redirect()->route('admin.directivos')->With(["message" => "Informacion del directivo actualizado correctamente: " . $personal->nombre . " " . $personal->apellidoP . " " . $personal->apellidoM, "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function materias()
    {
        $materias = materias::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/Materias', ['materias' => $materias, 'usuario' => $usuario]);
    }

    public function clases()
    {

        $personal = Personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->where('tipo_personal.tipo_personal', 'Profesor') //Le puse con mayuscula la P
            ->get();

        $clases = clases::all();
        $grupos = grupos::all();
        $gradosPrincipal = grados::with('ciclos')->get();
        $grados = $gradosPrincipal->map(function ($grado) {
            $grado->descripcion = $grado->grado . " - " . $grado->ciclos->descripcionCiclo;

            return $grado;
        });
        //$personal = personal::all();
        $materias = materias::all();
        $ciclos = ciclos::all();

        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/Clases', [
            'clases' => $clases,
            'grupos' => $grupos,
            'grados' => $grados,
            'personal' => $personal,
            'materias' => $materias,
            'ciclos' => $ciclos,
            'usuario' => $usuario
        ]);
    }

    public function tutores_alumnos()
    {
        // Obtención de datos de tutores
        $tutoresPrincipal = tutores::with(['generos', 'direcciones'])->get();
        //dd($tutoresPrincipal);

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
            $alumno->grado = $alumno->grados->grado;
            $alumno->grupo = $alumno->grupos->grupo;
            if ($alumno->materias != null) {
                $alumno->materia = $alumno->materias->materia;
            } else {
                $alumno->materia = "Ninguno";
            }
            $alumno->tutor = $alumno->tutores->nombre_completo;
            $alumno->tutorTel = $alumno->tutores->numTelefono;
            $alumno->tipoS = $alumno->tipo_Sangre->tipoSangre;
            $alumno->tutorC = $alumno->tutores;
            $alumno->gradoC = $alumno->grados;
            $alumno->grados->descripcion = $alumno->grados->grado . " - " . $alumno->grados->ciclos->descripcionCiclo;
            return $alumno;
        });

        $tipo_sangre = tipo_Sangre::all();

        $gradosPrincipal = grados::with('ciclos')->get();
        $grados = $gradosPrincipal->map(function ($grado) {
            $grado->descripcion = $grado->grado . " - " . $grado->ciclos->descripcionCiclo;

            return $grado;
        });

        $usuario = $this->obtenerInfoUsuario();
        $grupos = grupos::all();
        $materiasT = materias::where('esTaller', '1')->get();
        //$materias = materias::where('esTaller', 'true')->get();
        //$alumnos = alumnos::all();
        return Inertia::render('Admin/Tutores_Alumnos', [
            'tutores' => $tutores,
            'alumnos' => $alumnos,
            'generos' => $generos,
            'tipoSangre' => $tipo_sangre,
            'grados' => $grados,
            'grupos' => $grupos,
            'talleres' => $materiasT,
            'usuario' => $usuario
        ]);
    }

    public function obtenerGruposXGrado($idGrado)
    {
        $grado = grados::find($idGrado);
        $fecha = $grado->idCiclo;
        $gruposPr = grupos::where('idCiclo', $fecha)->get();
        $grupos = $gruposPr->map(function ($grupo) {
            $grupo->grupoC = $grupo->grupo . " - " . $grupo->ciclos->descripcionCiclo;

            return $grupo;
        });

        return response()->json($grupos);
    }

    public function obtenerCicloXGrado($idGrado)
    {
        try {
            $grado = grados::find($idGrado);
            $fecha = $grado->idCiclo;
            $ciclo = ciclos::where('idCiclo', $fecha)->get();

            return response()->json($ciclo);
        } catch (Exception $e) {
            dd($grado);
        }
    }

    public function gradosgrupos()
    {
        $ciclos = ciclos::all();
        $grados = grados::all();
        $grupos = grupos::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/GradosGrupos', [
            'ciclos' => $ciclos,
            'grados' => $grados,
            'grupos' => $grupos,
            'usuario' => $usuario
        ]);
    }

    public function ciclosperiodos()
    {
        $ciclosA = ciclos::all();
        $ciclos = $ciclosA->map(function ($ciclo) {
            $fecha_ic = Carbon::parse($ciclo->fecha_inicio)->format('d/m/Y');
            $fecha_fc = Carbon::parse($ciclo->fecha_fin)->format('d/m/Y');
            $ciclo->descCiclo = $ciclo->descripcionCiclo . " (" . $fecha_ic . " - " . $fecha_fc . ")";
            return $ciclo;
        });
        $periodos = periodos::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/CiclosPeriodos', [
            'ciclos' => $ciclos,
            'periodos' => $periodos,
            'usuario' => $usuario
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
                return redirect()->route('admin.tutoresAlum')->with(["message" => "El tutor ya está registrado o la dirección ya existe.", "color" => "red"]);
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
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Tutor agregado correctamente: " . $tutor->nombre . " " . $tutor->apellidoP . " " . $tutor->apellidoM . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||", "color" => "green"]);
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('admin.tutoresAlum')->With(["message" => "El tutor no se agrego correctamente", "color" => "red"]);
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
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Tutor actualizado correctamente: " . $tutor->nombre . " " . $tutor->apellidoP . " " . $tutor->apellidoM, "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "El tutor no se actualizo correctamente", "color" => "red"]);
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

            return redirect()->route('admin.tutoresAlum')->with(['message' => "Tutor eliminado correctamente", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al eliminar al tutor: Primero debe eliminar a los tutorados" . $e, "color" => "red"]);
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
            return redirect()->route('admin.tutoresAlum')->with(['message' => "Tutores eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al eliminar a los tutor: Primero debe eliminar a los tutorados " . $e, "color" => "red"]);
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
            ])->exists();
            
            if ($existingAlumno) {
                return redirect()->route('admin.tutoresAlum')->with(["message" => "El alumno ya está registrado.","color" => "red"]);
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
            $alumno->idGrado = $request->grado["idGrado"];
            $alumno->idGrupo = $request->grupo;
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
            return redirect()->route('admin.tutoresAlum')->with(['message' => "Alumno agregado correctamente: " . $nombreCompleto . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||.", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al agregar al alumno.", "color" => "red"]);
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
            $alumno = alumnos::find($request->idAlumno);
            $domicilio = direcciones::find($alumno->idDireccion);

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
            $alumno->idGrado = $request->grado["idGrado"];
            $alumno->idGrupo = $request->grupo;
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
            return redirect()->route('admin.tutoresAlum')->with(['message' => "Alumno actualizado correctamente: " . $nombreCompleto . ".", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al actualizar la informacion del alumno.", "color" => "red"]);
        }
    }

    public function eliminarAlumno($idAlumno)
    {
        try {
            $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'estudiante')->first();
            $alumno = alumnos::find($idAlumno);
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
            $alumno->delete();
            $usuarioTipoUsuario->delete();
            $usuario->delete();
            $direccion->delete();

            return redirect()->route('admin.tutoresAlum')->with(['message' => "Alumno eliminado correctamente.", "color" => "green"]);
        } catch (Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al eliminar al alumno. ", "color" => "red"]);
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
                $alumno->delete();
                $usuarioTipoUsuario->delete();
                $usuario->delete();
                $direccion->delete();
            }

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.tutoresAlum')->with(['message' => "Alumnos eliminados correctamente.", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('admin.tutoresAlum')->With(["message" => "Error al eliminar a los alumnos.", "color" => "red"]);
        }
    }

    public function addMaterias(Request $request)
    {
        // Verificar si la materia ya existe en la base de datos
        $existingMateria = Materias::where('materia', $request->materia)->first();
        //$existingMateria = Materias::where('materia', $request->materia)->whereNull('deleted_at')->first();

        if ($existingMateria) {
            // Si ya existe, puedes manejar la situación como desees, por ejemplo, redirigir con un mensaje de error.
            return redirect()->route('admin.materias')->with(['message'=> "La materia ya está registrada: " . $request->materia, "color" => "red"]);
        }

        // Si la materia no existe, proceder a agregarla a la base de datos
        $materia = new Materias();
        $materia->materia = $request->materia;
        $materia->descripcion = $request->descripcion;
        $materia->esTaller = $request->esTaller;

        $materia->save();

        return redirect()->route('admin.materias')->with(['message'=> "Materia agregada correctamente: " . $materia->materia, "color" => "green"]);
    }

    public function eliminarMaterias($idMateria)
    {
        $materia = materias::find($idMateria);
        $materia->delete();
        return redirect()->route('admin.materias')->with(['message' => "Materia eliminada correctamente", "color" => "green"]);
    }

    public function elimMaterias($materiasIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $materiasIdsArray = explode(',', $materiasIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $materiasIdsArray = array_map('intval', $materiasIdsArray);

            // Elimina las materias
            materias::whereIn('idMateria', $materiasIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.materias')->with(['message' => "Materias eliminadas correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            // Manejo de errores
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function actualizarMateria(Request $request, $idMateria)
    {

        $materias = materias::find($idMateria);
        $request->validate([
            'materia' => 'required',
            'descripcion' => 'required',
            'esTaller' => 'required',
        ]);

        $materias->fill($request->input())->saveOrFail();
        return redirect()->route('admin.materias')->with(['message'=> "Materia actualizada correctamente: " . $materias->materia, "color" => "green"]);
    }

    public function getMaterias($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsqueda
        $materias = materias::where('materia', 'like', '%' . $searchTerm . '%')
            ->orWhere('descripcion', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($materias);
    }

    public function addClases(Request $request)
    {
        try {
            $request->validate([
                'grupos' => 'required',
                'grados' => 'required',
                'personal' => 'required',
                'materias' => 'required',
                'ciclos' => 'required',
            ]);

            // Verificar si la clase ya existe
            $claseExistente = clases::where([
                'idGrado' => $request->grados['idGrado'],
                'idGrupo' => $request->grupos,
                'idPersonal' => $request->personal,
                'idMateria' => $request->materias,
                'idCiclo' => $request->ciclos,
            ])->first();

            if ($claseExistente) {
                return redirect()->route('admin.clases')->with(['message' => 'La clase no se puede agregar, porque ya se encunetra registrado.', "color" => "red"]);
            }

            // Crear y guardar la nueva clase
            $clase = new clases();
            $clase->idGrado = $request->grados['idGrado'];
            $clase->idGrupo = $request->grupos;
            $clase->idPersonal = $request->personal;
            $clase->idMateria = $request->materias;
            $clase->idCiclo = $request->ciclos;

            $clase->save();

            return redirect()->route('admin.clases')->with(['message' => "Clase agregada correctamente: " . $clase->materias->materia . ", " . $clase->grados->grado . " " . $clase->grupos->grupo . " " . $clase->ciclos->descripcionCiclo, "color" => "green"]);
        } catch (Exception $e) {
            Log::info('Error en guardar la clase: ' . $e);
            return redirect()->route('admin.clases')->withErrors(['message' => 'Error al guardar la clase.', "color" => "red"]);
        }
    }

    public function eliminarClases($idClase)
    {
        $clase = clases::find($idClase);
        $clase->delete();
        return redirect()->route('admin.clases')->with(['message' => "Clase eliminada correctamente", "color" => "green"]);
    }

    public function elimClases($clasesIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $clasesIdsArray = explode(',', $clasesIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $clasesIdsArray = array_map('intval', $clasesIdsArray);

            // Elimina los ciclos
            clases::whereIn('idClase', $clasesIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.clases')->with(['message' => "Clases eliminadas correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            // Manejo de errores
            dd("Controller error");
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function actualizarClases(Request $request, $idClase)
    {
        try {
            $clases = clases::find($idClase);
            $request->validate([
                'grados' => 'required',
                'grupos' => 'required',
                'personal' => 'required',
                'materias' => 'required',
                'ciclos' => 'required',
            ]);

            $clases->idGrupo = $request->grupos;
            $clases->idGrado = $request->grados;
            $clases->idPersonal = $request->personal;
            $clases->idMateria = $request->materias;
            $clases->idCiclo = $request->ciclos;

            $clases->fill($request->input())->saveOrFail();
            //return redirect()->route('admin.clases')->with('message',"Clase actualizada correctamente");

        } catch (Exception $e) {
            dd($e);
        }

        //$clases->fill($request->input())->saveOrFail();
        return redirect()->route('admin.clases')->with('message', "Clase actualizada correctamente");
    }

    public function getClases($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsqueda
        $clases = clases::where('clase', 'like', '%' . $searchTerm . '%')
            ->orWhere('idGrupo', 'like', '%' . $searchTerm . '%')
            ->orWhere('idGrado', 'like', '%' . $searchTerm . '%')
            ->orWhere('idProfesor', 'like', '%' . $searchTerm . '%')
            ->orWhere('idMateria', 'like', '%' . $searchTerm . '%')
            ->orWhere('idCiclo', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($clases);
    }

    public function addGrados(Request $request)
    {
        $request->validate([
            'ciclos' => 'required',
            'grado' => 'required',
        ]);

        // Verifica si ya existe un grado con el mismo valor en la base de datos
        $existingGrado = grados::where('grado', $request->grado)
            ->where('idCiclo', $request->ciclos)
            ->first();

        if ($existingGrado) {
            return redirect()->route('admin.gradosgrupos')->with(['message' => "El grado ya está registrado", "color" => "red"]);
        }

        $grado = new grados();
        $grado->grado = $request->grado;
        $grado->idCiclo = $request->ciclos;

        $grado->save();

        return redirect()->route('admin.gradosgrupos')->with(['message' => "Grado agregado correctamente: " . $grado->grado, "color" => "green"]);
    }

    public function eliminarGrados($idGrado)
    {
        $grado = grados::find($idGrado);
        $grado->delete();
        return redirect()->route('admin.gradosgrupos')->with(['message' => "Grado eliminado correctamente", "color" => "green"]);
    }

    public function elimGrados($gradosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $gradosIdsArray = explode(',', $gradosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $gradosIdsArray = array_map('intval', $gradosIdsArray);

            // Elimina los ciclos
            grados::whereIn('idGrado', $gradosIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación

            return redirect()->route('admin.gradosgrupos')->with(['message' =>'message', "Grados eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            // Manejo de errores
            dd($e);
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function actualizarGrados(Request $request, $idGrado)
    {
        try {
            $grados = grados::find($idGrado);
            $request->validate([
                'grado' => 'required',
                'ciclos' => 'required',
            ]);

            $grados->grado = $request->grado;
            $grados->idCiclo = $request->ciclos;

            $grados->fill($request->input())->saveOrFail();
        } catch (Exception $e) {
            dd($e);
        }

        //$grados->fill($request->input())->saveOrFail();
        return redirect()->route('admin.gradosgrupos')->with(['message' => "Grado actualizado correctamente: " . $grados->grado, "color" => "green"]);
    }

    public function getGrados($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsquedastoo
        $grados = grados::where('grado', 'like', '%' . $searchTerm . '%')
            ->orWhere('grado', 'like', '%' . $searchTerm . '%')
            ->orWhere('idCiclo', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($grados);
    }

    public function getGrupos($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsqueda
        $grupos = grupos::where('grupo', 'like', '%' . $searchTerm . '%')
            ->orWhere('grupo', 'like', '%' . $searchTerm . '%')
            ->orWhere('idCiclo', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($grupos);
    }

    public function addGrupos(Request $request)
    {
        $request->validate([
            'grupo' => 'required',
            'ciclos' => 'required',
        ]);

        // Verificar si ya existe un grupo con los mismos datos
        $existingGroup = grupos::where('grupo', $request->grupo)
            ->where('idCiclo', $request->ciclos)
            ->first();

        if ($existingGroup) {
            return redirect()->route('admin.gradosgrupos')->with(['message' => 'El grupo ya está registrado.', "color" => "red"]);
        }

        // Si no existe, proceder con el registro
        $grupo = new grupos();
        $grupo->grupo = $request->grupo;
        $grupo->idCiclo = $request->ciclos;
        $grupo->save();

        return redirect()->route('admin.gradosgrupos')->with(['message' => "Grupo agregado correctamente: " . $grupo->grupo, "color" => "green"]);
    }

    public function eliminarGrupos($idGrupo)
    {
        $grupo = grupos::find($idGrupo);
        $grupo->delete();
        return redirect()->route('admin.gradosgrupos')->with(['message' =>'message', "Grupo eliminada correctamente", "color" => "green"]);
    }

    public function elimGrupos($gruposIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $gruposIdsArray = explode(',', $gruposIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $gruposIdsArray = array_map('intval', $gruposIdsArray);

            // Elimina los ciclos
            grupos::whereIn('idGrupo', $gruposIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.gradosgrupos')->with(['message' => "Grupos eliminadas correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al eliminar'
            ], 500);
        }
    }

    public function actualizarGrupos(Request $request, $idGrupo)
    {
        try {
            $grupos = grupos::find($idGrupo);
            $request->validate([
                'grupo' => 'required',
                'ciclos' => 'required',
            ]);
            $grupos->grupo = $request->grupo;
            $grupos->idCiclo = $request->ciclos;

            $grupos->fill($request->input())->saveOrFail();
        } catch (Exception $e) {
        }
        return redirect()->route('admin.gradosgrupos')->with(['message' => "Grupo actualizado correctamente: " . $grupos->grupo, "color" => "green"]);
    }

    public function addCiclos(Request $request)
    {
        // Validación para evitar ciclos duplicados
        $existingCiclo = Ciclos::where('fecha_inicio', $request->fecha_inicio)
            ->where('fecha_fin', $request->fecha_fin)
            ->where('descripcionCiclo', $request->descripcionCiclo)
            ->first();

        if ($existingCiclo) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "El ciclo ya se encuentra registrado", "color" => "red"]);
        }

        // Verificar si las fechas del nuevo ciclo se superponen con otro ciclo existente
        $conflictingCiclo = Ciclos::where(function ($query) use ($request) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('fecha_inicio', '>=', $request->fecha_inicio)
                    ->where('fecha_inicio', '<=', $request->fecha_fin);
            })
                ->orWhere(function ($subquery) use ($request) {
                    $subquery->where('fecha_fin', '>=', $request->fecha_inicio)
                        ->where('fecha_fin', '<=', $request->fecha_fin);
                });
        })
            ->first();

        if ($conflictingCiclo) {
            return redirect()->route('admin.ciclosperiodos')->with(["message" => "No se puede agregar el ciclo, las fechas se superponen con otro ciclo.", "color" => "red"]);
        }

        $anioInicio = date('Y', strtotime($request->fecha_inicio));
        $anioFin = date('Y', strtotime($request->fecha_fin));

        // Si no hay ciclos duplicados, procede con la creación y guardado del nuevo ciclo
        $ciclo = new Ciclos();
        $ciclo->fecha_inicio = $request->fecha_inicio;
        $ciclo->fecha_fin = $request->fecha_fin;

        $ciclo->descripcionCiclo = $anioInicio . "-" . $anioFin;
        //$ciclo->descripcionCiclo = $request->descripcionCiclo;

        $ciclo->save();
        return redirect()->route('admin.ciclosperiodos')->With(["message" => "Ciclo agregado correctamente: " . $ciclo->descripcionCiclo, "color" => "green"]);
    }

    public function eliminarCiclos($idCiclo)
    {
        try {
            $ciclo = ciclos::find($idCiclo);
            if (!$ciclo) {
                return redirect()->route('admin.ciclosperiodos')->With(["message" => "El ciclo no existe", "color" => "red"]);
            }
            $ciclo->delete();
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Ciclo eliminado correctamente", "color" => "green"]);
        } catch (QueryException $e) {
            // Verificar si la excepción es debida a una restricción de clave externa
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1451) {
                // El código 1451 generalmente indica una violación de clave externa
                return redirect()->route('admin.ciclosperiodos')->With(["message" => "Es necesario que se elimine los datos que hagan referecia a este ciclo", "color" => "red"]);
            }
        } catch (Exception $e) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al eliminar el ciclo", "color" => "red"]);
        }
    }

    public function elimCiclos($ciclosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $ciclosIdsArray = explode(',', $ciclosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $ciclosIdsArray = array_map('intval', $ciclosIdsArray);

            // Elimina los ciclos
            ciclos::whereIn('idCiclo', $ciclosIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Ciclos eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al eliminar los ciclos", "color" => "red"]);
        }
    }

    public function actualizarCiclos(Request $request, $idCiclo)
    {
        try {
            $ciclos = ciclos::find($idCiclo);
            $request->validate([
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'descripcionCiclo' => 'required',
            ]);

            // Validación para evitar ciclos duplicados
            $existingCiclo = Ciclos::where('idCiclo', '!=', $idCiclo)
                ->where('fecha_inicio', $request->fecha_inicio)
                ->where('fecha_fin', $request->fecha_fin)
                ->where('descripcionCiclo', $request->descripcionCiclo)
                ->first();

            if ($existingCiclo) {
                return redirect()->route('admin.ciclosperiodos')->with(["message" => "El ciclo ya se encuentra registrado", "color" => "red"]);
            }

            // Verificar si las fechas del ciclo actualizado se superponen con otro ciclo existente
            $conflictingCiclo = Ciclos::where('idCiclo', '!=', $idCiclo)
                ->where(function ($query) use ($request) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->where('fecha_inicio', '<=', $request->fecha_fin)
                            ->where('fecha_fin', '>=', $request->fecha_inicio);
                    })
                        ->orWhere(function ($subquery) use ($request) {
                            $subquery->where('fecha_inicio', '>=', $request->fecha_inicio)
                                ->where('fecha_inicio', '<=', $request->fecha_fin)
                                ->where('fecha_fin', '>=', $request->fecha_fin);
                        });
                })
                ->first();

            if ($conflictingCiclo) {
                return redirect()->route('admin.ciclosperiodos')->with(["message" => "No se puede actualizar el ciclo, las fechas se superponen con otro ciclo.", "color" => "red"]);
            }
            $anioInicio = date('Y', strtotime($request->fecha_inicio));
            $anioFin = date('Y', strtotime($request->fecha_fin));

            $ciclos->fecha_inicio = $request->fecha_inicio;
            $ciclos->fecha_fin = $request->fecha_fin;
            $ciclos->descripcionCiclo = $anioInicio . "-" . $anioFin;

            $ciclos->save();
        } catch (Exception $e) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al actualizar el ciclo", "color" => "red"]);
        }
        return redirect()->route('admin.ciclosperiodos')->With(["message" => "Ciclo actualizado correctamente: " . $ciclos->descripcionCiclo, "color" => "green"]);
    }

    public function getCiclos($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsqueda
        $ciclos = ciclos::where('grupo', 'like', '%' . $searchTerm . '%')
            ->orWhere('fecha_inicio', 'like', '%' . $searchTerm . '%')
            ->orWhere('fecha_fin', 'like', '%' . $searchTerm . '%')
            ->orWhere('descripcionCiclo', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($ciclos);
    }

    public function addPeriodos(Request $request)
    {
        $request->validate([
            'periodo' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'ciclos' => 'required',
        ]);

        // Verificar si ya existe un periodo con los mismos datos
        $existingPeriodo = periodos::where('periodo', $request->periodo)
            ->where('fecha_inicio', $request->fecha_inicio)
            ->where('fecha_fin', $request->fecha_fin)
            ->where('idCiclo', $request->ciclos)
            ->first();

        if ($existingPeriodo) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => 'El periodo ya existe en la base de datos.', "color" => "red"]);
        }

        // Verificar si las fechas están dentro de otro periodo
        $conflictingPeriodo = periodos::where('idCiclo', $request->ciclos)
            ->where(function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->where('fecha_inicio', '>=', $request->fecha_inicio)
                        ->where('fecha_inicio', '<=', $request->fecha_fin);
                })
                    ->orWhere(function ($subquery) use ($request) {
                        $subquery->where('fecha_fin', '>=', $request->fecha_inicio)
                            ->where('fecha_fin', '<=', $request->fecha_fin);
                    });
            })
            ->first();

        if ($conflictingPeriodo) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => 'No se puede agregar un periodo dentro de las fechas de otro.', "color" => "red"]);
        }


        // Si no existe, proceder con la inserción
        $periodo = new periodos();
        $periodo->periodo = $request->periodo;
        $periodo->fecha_inicio = $request->fecha_inicio;
        $periodo->fecha_fin = $request->fecha_fin;
        $periodo->idCiclo = $request->ciclos;

        $periodo->save();

        return redirect()->route('admin.ciclosperiodos')->With(["message" => "Periodo agregado correctamente: " . $periodo->periodo, "color" => "green"]);
    }

    public function eliminarPeriodos($idPeriodo)
    {
        try {
            $periodo = periodos::find($idPeriodo);
            $periodo->delete();
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Periodo eliminado correctamente", "color" => "green"]);
        } catch (Exception $a) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al eliminar el periodo", "color" => "red"]);
        }
    }

    public function elimPeriodos($periodosIds)
    {
        try {
            // Convierte la cadena de IDs en un array
            $periodosIdsArray = explode(',', $periodosIds);

            // Limpia los IDs para evitar posibles problemas de seguridad
            $periodosIdsArray = array_map('intval', $periodosIdsArray);

            // Elimina los ciclos
            periodos::whereIn('idPeriodo', $periodosIdsArray)->delete();

            // Redirige a la página deseada después de la eliminación
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Periodos eliminados correctamente", "color" => "green"]);
        } catch (\Exception $e) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al eliminar los periodos", "color" => "red"]);
        }
    }

    public function actualizarPeriodos(Request $request, $idPeriodo)
    {
        try {
            $periodos = periodos::find($idPeriodo);
            $request->validate([
                'periodo' => 'required',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'ciclos' => 'required',
            ]);
            $periodos->periodo = $request->periodo;
            $periodos->fecha_inicio = $request->fecha_inicio;
            $periodos->fecha_fin = $request->fecha_fin;
            $periodos->idCiclo = $request->ciclos;

            // Verificar si ya existe un periodo con los mismos datos
            $existingPeriodo = periodos::where('idPeriodo', '!=', $idPeriodo) // Excluir el propio periodo que se está actualizando
                ->where('periodo', $request->periodo)
                ->where('fecha_inicio', $request->fecha_inicio)
                ->where('fecha_fin', $request->fecha_fin)
                ->where('idCiclo', $request->ciclos)
                ->first();

            if ($existingPeriodo) {
                return redirect()->route('admin.ciclosperiodos')->with(["message" => "El periodo ya existe en la base de datos.", "color" => "red"]);
            }

            // Verificar si las fechas están dentro de otro periodo
            $conflictingPeriodo = periodos::where('idCiclo', $request->ciclos)
                ->where('idPeriodo', '!=', $idPeriodo) // Excluir el propio periodo que se está actualizando
                ->where(function ($query) use ($request) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->where('fecha_inicio', '>=', $request->fecha_inicio)
                            ->where('fecha_inicio', '<=', $request->fecha_fin);
                    })
                        ->orWhere(function ($subquery) use ($request) {
                            $subquery->where('fecha_fin', '>=', $request->fecha_inicio)
                                ->where('fecha_fin', '<=', $request->fecha_fin);
                        });
                })
                ->first();

            if ($conflictingPeriodo) {
                return redirect()->route('admin.ciclosperiodos')->with(["message" => "No se puede actualizar el periodo con fechas que se superponen con otro periodo.", "color" => "red"]);
            }

            $periodos->fill($request->input())->saveOrFail();
        } catch (Exception $e) {
            return redirect()->route('admin.ciclosperiodos')->With(["message" => "Error al actualizar el periodo", "color" => "red"]);
        }
        return redirect()->route('admin.ciclosperiodos')->With(["message" => "Periodo actualizado correctamente", "color" => "green"]);
    }

    public function getPeriodos($searchTerm)
    {
        // Lógica para obtener las materias según el término de búsqueda
        $periodos = periodos::where('grupo', 'like', '%' . $searchTerm . '%')
            ->orWhere('periodo', 'like', '%' . $searchTerm . '%')
            ->orWhere('fecha_inicio', 'like', '%' . $searchTerm . '%')
            ->orWhere('fecha_fin', 'like', '%' . $searchTerm . '%')
            ->orWhere('idCiclo', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json($periodos);
    }

    public function usuarios()
    {
        $usuarios = usuarios::all();
        $usuario = $this->obtenerInfoUsuario();

        return Inertia::render('Admin/Usuarios', [
            'usuarios' => $usuarios,
            'usuario' => $usuario
        ]);
    }

    public function addUsuarios(Request $request)
    {
        if (Auth::check()) {
            try {
                $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'administrador')->first();
                $usuario = new usuarios();
                $usuario->usuario = $request->usuario;
                $usuario->contrasenia = $request->contrasenia;
                $usuario->password = bcrypt($request->contrasenia);
                $usuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
                $usuario->save();
                return redirect()->route('admin.usuarios')->With(["message" => "Usuario agregado correctamente: " . " || \nUsuario: " . $usuario->usuario . " || \nContraseña: " . $usuario->contrasenia . " ||.", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('admin.usuarios')->with(['message' => "Error al agregar el usuario", "color" => "red"]);
            }
        }
        return redirect()->route('admin.inicio')->With(["message" => "No tienes acceso a esta función", "color" => "red"]);
    }

    public function elimUsuarios($usuariosIds)
    {
        if (Auth::check()) {
            try {
                // Convierte la cadena de IDs en un array
                $usuariosIdsArray = explode(',', $usuariosIds);

                // Limpia los IDs para evitar posibles problemas de seguridad
                $usuariosIdsArray = array_map('intval', $usuariosIdsArray);

                // Elimina los ciclos
                usuarios::whereIn('idUsuario', $usuariosIdsArray)->delete();

                // Redirige a la página deseada después de la eliminación
                return redirect()->route('admin.usuarios')->With(["message" => "Usuarios eliminados correctamente.", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('admin.usuarios')->With(["message" => "Error al eliminar a los usuarios, es necesario eliminar los datos de las personas que tienen asignados dichos usuarios.", "color" => "red"]);
            }
        }
        return redirect()->route('admin.inicio')->With(["message" => "No tienes acceso a esta función", "color" => "red"]);
    }

    public function eliminarUsuarios($idUsuario)
    {
        if (Auth::check()) {
            try {
                $usuario = usuarios::find($idUsuario);
                $usuario->delete();
                return redirect()->route('admin.usuarios')->With(["message" => "Usuario eliminado correctamente.", "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('admin.usuarios')->With(["message" => "Error al eliminar al usuario, es necesario eliminar los datos de la persona que tiene asignado dicho usuario.", "color" => "red"]);
            }
        }
        return redirect()->route('admin.inicio')->With(["message" => "No tienes acceso a esta función", "color" => "red"]);
    }

    public function actualizarUsuarios(Request $request, $idUsuario)
    {
        if (Auth::check()) {
            try {
                $usuarios = usuarios::find($idUsuario);
                $request->validate([
                    'usuario' => 'required',
                    'contrasenia' => 'required',
                ]);
                $usuarios->usuario = $request->usuario;
                $usuarios->contrasenia = $request->contrasenia;
                $usuarios->password = bcrypt($request->contrasenia);

                $usuarios->save();
                return redirect()->route('admin.usuarios')->With(["message" => "Usuario actualizado correctamente "  . $usuarios->usuario, "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('admin.usuarios')->With(["message" => "Error al actualizar el usuario", "color" => "red"]);
            }
        }
        return redirect()->route('admin.inicio')->With(["message" => "No tienes acceso a esta función", "color" => "red"]);
    }

    public function obtenerUsuario()
    {
        return auth()->user();
    }

    public function obtenerTipoUsuario($idTipoUsuario)
    {
        return tipoUsuarios::find($idTipoUsuario);
    }

    public function obtenerDatosPersonal($idUsuario)
    {
        try {
            $usuario = usuarios::where('idUsuario', $idUsuario)->with(['personal'])->first();
            return $usuario->personal;
        } catch (Exception $e) {
            Log::info($e);
            return ['personal' => 'Sin asignar'];
        }
    }

    public function obtenerInfoUsuario()
    {
        $idUsuario = auth()->user()->idUsuario;
        $usuario = usuarios::find($idUsuario);
        $usuario->tipoUsuario1 = $usuario->tipoUsuarios->tipoUsuario;
        return $usuario;
    }

    public function perfil()
    {
        try {
            $usuario = $this->obtenerInfoUsuario();
            $personal = personal::where('idUsuario', $usuario->idUsuario)->with(['generos', 'tipo_sangre', 'direcciones'])->first();

            return Inertia::render('Admin/Contrasenia', [
                'usuario' => $usuario,
                'director' => $personal,
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

                return redirect()->route('admin.contrasenia')->With(["message" => "Contraseña actualizada correctamente, recuerde su contraseña: " . $usuario->contrasenia, "color" => "green"]);
            }
            return redirect()->route('admin.contrasenia')->With(["message" => "Contraseña actual incorrecta", "color" => "red"]);
        } catch (Exception $e) {
            return redirect()->route('admin.contrasenia')->With(["message" => "Error al actualizar contraseña", "color" => "red"]);
            dd($e);
        }
    }

    public function restaurarUsuario($idUsuario)
    {
        if (Auth::check()) {
            try {
                $usuario = Usuarios::where("idUsuario", $idUsuario)->first();
                $usuario->intentos = 10;
                $usuario->fecha_Creacion = now();
                $usuario->save();
                return redirect()->route('admin.usuarios')->With(["message" => "Usuario restaurado correctamente: " . $usuario->usuario, "color" => "green"]);
            } catch (Exception $e) {
                return redirect()->route('admin.usuarios')->With(["message" => "Error al restaurar al usuario", "color" => "red"]);
            }
        }
        return redirect()->route('admin.inicio')->With(["message" => "No tienes acceso a esta función", "color" => "red"]);
    }
}
