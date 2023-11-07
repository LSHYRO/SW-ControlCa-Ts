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
use App\Models\tipoUsuarios;
use App\Models\usuarios_tiposUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

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

        return Inertia::render('Admin/Profesores', [
            'personal' => $personal,
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

        return Inertia::render('Admin/Materias');
    }

    public function clases()
    {
        $clases = clases::all();

        return Inertia::render('Admin/Clases');
    }

    public function tutores()
    {
        $tutores = tutores::all();
        $estados = estados::all();

        return view('/administrador/tutores', compact('tutores', 'estados'));
    }

    public function gradosgrupos()
    {
        $grados = grados::all();
        $grupos = grupos::all();

        return Inertia::render('Admin/GradosGrupos');
    }

    public function ciclosperiodos()
    {
        $ciclos = ciclos::all();
        $periodos = periodos::all();

        return Inertia::render('Admin/CiclosPeriodos');
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
            ->where('idTipoUsuario',$tipoUsuario->idTipoUsuario)
            ->first();
        $personal->delete();
        $usuarioTipoUsuario->delete();
        $usuario ->delete();
        return redirect()->route('admin.profesores');
    }


    public function actualizarProfesor(Request $request, $idPersonal){

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
        $materia->activo = $request->activo;
        $materia->extracurricular = $request->extracurricular;

        $materia->save();
        return redirect()->route('admin.materias');
    }

    public function eliminarMaterias($idMateria)
    {
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
    }

    public function actualizarMateria(Request $request, $idPersonal){

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
    }

    public function actualizarClases(Request $request, $idPersonal){

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
        $grado = new grados();
        $grado->grado = $request->grado;
        $grado->ciclo = $request->ciclo;

        $clase->save();
        return redirect()->route('admin.gradosgrupos');
    }
    public function addGrupos(Request $request)
    {
        $grupo = new grupos();
        $grupo->grupo = $request->grupo;
        $grupo->ciclo = $request->ciclo;

        $clase->save();
        return redirect()->route('admin.gradosgrupos');
    }

    public function addCiclos(Request $request)
    {
        $ciclo = new ciclos();
        $ciclo->ciclo = $request->ciclo;
        $ciclo->fecha_inicio = $request->fecha_inicio;
        $ciclo->fecha_fin = $request->fecha_fin;
        $ciclo->descripcion = $request->descripcion;
        $ciclo->activo = $request->activo;

        $ciclo->save();
        return redirect()->route('admin.ciclosperiodos');
    }
    public function addPeriodos(Request $request)
    {
        $periodo = new periodos();
        $periodo->periodo = $request->perido;
        $periodo->fecha_inicio = $request->fecha_inicio;
        $periodo->fecha_fin = $request->fecha_fin;
        $periodo->activo = $request->activo;
        $periodo->ciclo = $request->ciclo;

        $periodo->save();
        return redirect()->route('admin.ciclosperiodos');
    }
}
