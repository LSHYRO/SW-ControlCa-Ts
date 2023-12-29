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
use App\Models\grados;
use App\Models\grupos;
use App\Models\personal;
use App\Models\personal_escolar;
use App\Models\tipo_personal;
use App\Models\tipo_Sangre;
use App\Models\tipoUsuarios;
use App\Models\usuarios_tiposUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Principal');
    }

    public function inicio()
    {
        return Inertia::render('Admin/Inicio');
    }

    public function profesores()
    {
        $personal = personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->where('tipo_personal.tipo_personal', 'profesor')
            ->get();
        $tipoSangre = tipo_Sangre::all();

        return Inertia::render('Admin/Profesores', [
            'personal' => $personal, 'tipoSangre' => $tipoSangre
        ]);
    }

    public function alumnos()
    {
        $alumnos = alumnos::all();

        return view('/administrador/alumnos', compact('alumnos'));
    }

    public function directivos()
    {
        $personal = personal::join('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id_tipo_personal')
            ->where('tipo_personal.tipo_personal', 'personal_escolar')
            ->get();

        return view('/administrador/directivos', compact('personal'));
    }

    public function materias()
    {
        $materias = materias::all();
        return Inertia::render('Admin/Materias', ['materias' => $materias]);
    }

    public function clases()
    {
        $clases = clases::all();

        return Inertia::render('Admin/Clases');
    }

    public function tutores_alumnos()
    {
        $tutores = tutores::all();
        $alumnos = alumnos::all();
        return Inertia::render('Admin/Tutores_Alumnos', compact('tutores', 'alumnos'));
    }
    /*
    public function tutores()
    {
        $tutores = tutores::all();
        $estados = estados::all();

        return view('/administrador/tutores', compact('tutores', 'estados'));
    }
*/
    public function gradosgrupos()
    {
        $ciclos = ciclos::all();
        $grados = grados::all();
        $grupos = grupos::all();

        return Inertia::render('Admin/GradosGrupos',[
            'ciclos' => $ciclos,
            'grados' => $grados,
            'grupos' => $grupos,
        ]);
    }
    
    public function ciclosperiodos()
    {
        $ciclos = ciclos::all();
        $periodos = periodos::all();

        return Inertia::render('Admin/CiclosPeriodos', [
            'ciclos' => $ciclos,
            'periodos' => $periodos,
        ]);
    }

    public function addProfesores(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellidoP' => 'required',
            'apellidoM' => 'required',
            'numTelefono' => 'required',
            'correoElectronico' => 'required',
            'fechaNacimiento' => 'required',
            'CURP' => 'required',
            'RFC' => 'required',
            'tipoSangre' => 'required',
            'alergias' => 'required',
            'discapacidad' => 'required',
            'direccion' => 'required',
            'tipo_personal' => 'required',
            'usuario' => 'required',
        ]);

        //fechaFormateada
        $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
        //Contraseña generada
        $contrasenia = Str::random(8);
        //Creacion de usuario
        $usuario = new usuarios();
        $usuario->usuario = strtolower(substr($request->apellidoP, 0, 2) . substr($request->apellidoM, 0, 1) . substr($request->nombre, 0, 1) . $fechaFormateada . Str::random(3));
        $usuario->contrasenia = $contrasenia; //Hash::make($contrasenia);
        //$usuario->activo = 1;
        //echo "Tu contraseña generada es: $contrasenia";
        //return $usuario -> contrasenia . " " . Hash::check($contrasenia,$usuario -> contrasenia);
        $usuario->save();

        //Se busca el tipo de usuario en la BD
        $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();

        $usuarioTipoUsuario = new usuarios_tiposUsuarios();
        $usuarioTipoUsuario->idUsuario = $usuario->idUsuario;
        $usuarioTipoUsuario->idTipoUsuario = $tipoUsuario->idTipoUsuario;
        $usuarioTipoUsuario->save();

        //Se busca el tipo de personal en la BD
        $tipo_personal = tipo_personal::where('tipo_personal', 'profesor')->first();

        $personal = new personal($request->input());
        $personal->idUsuario = $usuario->idUsuario;
        $personal->id_tipo_personal = $tipo_personal->id_tipo_personal;
        //$personal->activo = 1;

        //columna nombre completo
        $nombreCompleto = $personal->nombre . ' ' . $personal->apellidoP . ' ' . $personal->apellidoM;
        $personal->nombre_completo = $nombreCompleto;

        //Guardado
        $personal->save();
        return redirect()->route('admin.profesores');
    }

    public function addTutores(Request $request)
    {
        //fechaFormateada
        $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
        //Contraseña generada
        $contrasenia = Str::random(8);
        //Creacion de usuario
        $usuario = new usuarios();
        $usuario->usuario = strtolower(substr($request->apellidoPaterno, 0, 2) . substr($request->apellidoMaterno, 0, 1) . substr($request->nombres, 0, 1) . $fechaFormateada . Str::random(3));
        $usuario->contrasenia = $contrasenia; //Hash::make($contrasenia);
        $usuario->activo = 1;
        //echo "Tu contraseña generada es: $contrasenia";
        //return $usuario -> contrasenia . " " . Hash::check($contrasenia,$usuario -> contrasenia);
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
        $direccion->colonia = $request->colonia;
        $direccion->municipio = $request->municipio;
        $direccion->ciudad = $request->region;
        $direccion->idEstado = $request->estado;
        $direccion->save();

        $tutor = new tutores();
        $tutor->nombre = $request->nombres;
        $tutor->apellidoP = $request->apellidoPaterno;
        $tutor->apellidoM = $request->apellidoMaterno;
        $tutor->idUsuario = $usuario->idUsuario;
        $tutor->numTelefono = $request->telefono;
        $tutor->idDireccion = $direccion->idDireccion;
        $tutor->activo = 1;

        //columna nombre completo
        $nombreCompleto = $tutor->nombre . ' ' . $tutor->apellidoP . ' ' . $tutor->apellidoM;
        $tutor->nombre_completo = $nombreCompleto;

        //Guardado
        $tutor->save();

        return redirect()->route('admin.tutores');
    }

    public function buscarT(Request $request)
    {
        $query = $request->input('query');

        // Realiza la búsqueda full-text en la columna "nombre_completo"
        $tutores = tutores::whereRaw('MATCH(nombre_completo) AGAINST(? IN BOOLEAN MODE)', [$query])
            ->get();

        // Formatea los resultados para enviarlos como respuesta JSON
        $results = [];
        foreach ($tutores as $tutor) {
            $results[] = [
                'id' => $tutor->idTutor, // Corrige esto para usar el campo idTutor
                'text' => $tutor->nombre_completo // El nombre completo del tutor
            ];
        }

        return response()->json($results);
    }

    public function eliminarProfesores($idPersonal)
    {
        $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();

        $personal = personal::find($idPersonal);
        $usuario = usuarios::find($personal->idUsuario);
        $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
            ->where('idTipoUsuario', $tipoUsuario->idTipoUsuario)
            ->first();
        $personal->delete();
        $usuarioTipoUsuario->delete();
        $usuario->delete();
        return redirect()->route('admin.profesores');
    }


    public function actualizarProfesor(Request $request, $idPersonal)
    {

        $personal = personal::find($idPersonal);
        $request->validate([
            'nombre' => 'required',
            'apellidoP' => 'required',
            'apellidoM' => 'required',
            'nombre' => 'required',
            'numTelefono' => 'required',
            'correoElectronico' => 'required',
            'fechaNacimiento' => 'required',
        ]);

        $personal->fill($request->input())->saveOrFail();
        return redirect()->route('admin.profesores');
    }

    public function addMaterias(Request $request)
    {
    
        $materia = new materias();
        $materia->materia = $request->materia;
        $materia->descripcion = $request->descripcion;
        $materia->esTaller = $request->esTaller;
        $materia->esTaller = $request->esTaller;

        $materia->save();
        return redirect()->route('admin.materias')->with('message', "Materia agregada correctamente: ".$materia->materia);
    }

    public function eliminarMaterias($idMateria)
    {
        $materia = materias::find($idMateria);
        $materia->delete();
        return redirect()->route('admin.materias')->with('message', "Materia eliminada correctamente");
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
        return redirect()->route('admin.materias')->with('message', "Materias eliminadas correctamente");
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
        return redirect()->route('admin.materias')->with('message', "Materia actualizada correctamente: ". $materias->materia);;
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
        $clase = new clases();
        $clase->clase = $request->clase;
        $clase->hora = $request->hora;
        $clase->dias = $request->dias;
        $clase->hora = $request->hora;
        $clase->grupo = $request->grupo;
        $clase->grado = $request->grado;
        $clase->docente = $request->docente;
        $clase->materia = $request->materia;
        $clase->ciclo = $request->ciclo;

        $clase->save();
        return redirect()->route('admin.clases');
    }

    public function eliminarClases($idClase)
    {
        /*
        $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();

        $personal = personal::find($idPersonal);
        $usuario = usuarios::find($personal->idUsuario);
        $usuarioTipoUsuario = usuarios_tiposUsuarios::where('idUsuario', $usuario->idUsuario)
            ->where('idTipoUsuario',$tipoUsuario->idTipoUsuario)
            ->first();
        $personal->delete();
        $usuarioTipoUsuario->delete();
        $usuario ->delete();
        return redirect()->route('admin.profesores');
        */
    }

    public function actualizarClases(Request $request, $idPersonal)
    {

        $personal = personal::find($idPersonal);
        $request->validate([
            'nombre' => 'required',
            'apellidoP' => 'required',
            'apellidoM' => 'required',
            'nombre' => 'required',
            'numTelefono' => 'required',
            'correoElectronico' => 'required',
            'fechaNacimiento' => 'required',
        ]);

        $personal->fill($request->input())->saveOrFail();
        return redirect()->route('admin.profesores');
    }

    public function addGrados(Request $request)
    {
        $request->validate([
            'ciclos' => 'required',
        ]);
        
        $grado = new grados();
        $grado->grado = $request->grado;
        $grado->idCiclo = $request->ciclos;

        $grado->save();
        return redirect()->route('admin.gradosgrupos');
        
    }

    public function eliminarGrados($idGrado)
    {
        $grado = grados::find($idGrado);
        $grado->delete();
        return redirect()->route('admin.gradosgrupos')->with('message', "Grado eliminado correctamente");
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
        return redirect()->route('admin.gradosgrupos')->with('message', "Grados eliminados correctamente");
    } catch (\Exception $e) {
        // Manejo de errores
        dd("Controller error");
        return response()->json([
            'error' => 'Ocurrió un error al eliminar'
        ], 500);
    }
}

    public function addGrupos(Request $request)
    {
        $request->validate([
            'ciclos' => 'required',
        ]);
        
        $grupo = new grupos();
        $grupo->grupo = $request->grupo;
        $grupo->idCiclo = $request->ciclos;

        $grupo->save();
        return redirect()->route('admin.gradosgrupos');
        
    }

    public function eliminarGrupos($idGrupo)
    {
        $grupo = grupos::find($idGrupo);
        $grupo->delete();
        return redirect()->route('admin.gradosgrupos')->with('message', "Grupo eliminada correctamente");
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
        return redirect()->route('admin.gradosgrupos')->with('message', "Grupos eliminadas correctamente");
    } catch (\Exception $e) {
        // Manejo de errores
        dd("Controller error");
        return response()->json([
            'error' => 'Ocurrió un error al eliminar'
        ], 500);
    }
}

    public function addCiclos(Request $request)
    {
        $ciclo = new ciclos();
        $ciclo->fecha_inicio = $request->fecha_inicio;
        $ciclo->fecha_fin = $request->fecha_fin;
        $ciclo->descripcionCiclo = $request->descripcionCiclo;

        $ciclo->save();
        return redirect()->route('admin.ciclosperiodos');
    }

    public function eliminarCiclos($idCiclo)
    {
        $ciclo = ciclos::find($idCiclo);
        $ciclo->delete();
        return redirect()->route('admin.ciclosperiodos')->with('message', "Materia eliminada correctamente");
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
        return redirect()->route('admin.ciclosperiodos')->with('message', "Ciclos eliminadas correctamente");
    } catch (\Exception $e) {
        // Manejo de errores
        dd("Controller error");
        return response()->json([
            'error' => 'Ocurrió un error al eliminar'
        ], 500);
    }
}

    public function addPeriodos(Request $request)
    {
        $request->validate([
            'ciclos' => 'required',
        ]);
        
        $periodo = new periodos();
        $periodo->periodo = $request->periodo;
        $periodo->fecha_inicio = $request->fecha_inicio;
        $periodo->fecha_fin = $request->fecha_fin;
        $periodo->idCiclo= $request->ciclos;

        $periodo->save();
        return redirect()->route('admin.ciclosperiodos');
    }

    public function eliminarPeriodos($idPeriodo)
    {
        $periodo = periodos::find($idPeriodo);
        $periodo->delete();
        return redirect()->route('admin.ciclosperiodos')->with('message', "Periodo eliminada correctamente");
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
        return redirect()->route('admin.ciclosperiodos')->with('message', "Periodos eliminados correctamente");
    } catch (\Exception $e) {
        // Manejo de errores
        dd("Controller error");
        return response()->json([
            'error' => 'Ocurrió un error al eliminar'
        ], 500);
    }
}
}