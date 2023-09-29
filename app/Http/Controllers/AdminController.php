<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\alumnos;
use App\Models\materias;
use App\Models\tutores;
use App\Models\direcciones;
use App\Models\personal_escolar;
use App\Models\tipoUsuarios;
use App\Models\usuarios_tiposUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        //return "Bienvenido a la pagina principal";
        return view('/administrador/inicio');
    }

    public function profesores()
    {   
        
        $profesores = profesores::all();
        $usuarios = usuarios::all();
        $personas = personas::all();

        //return "Bienvenido a la pagina principal";
        return view('/administrador/profesores', compact('profesores','personas'));
    }

    public function alumnos(){
        $alumnos = alumnos::all();
        $usuarios = usuarios::all();
        $personas = personas::all();

        return view('/administrador/alumnos', compact('alumnos','personas'));
    }

    public function directivos(){
        $directivos = personal_escolar::all();
        //$personas = personas::all();
        //$direcciones = direcciones::all();

        return view('/administrador/directivos');
    }

    public function materias(){
        $materias = materias::all();
        
        return view('/administrador/materias', compact('materias'));
    }

    public function tutores(){
        $tutores = tutores::all();
        $personas = personas::all();
        $direcciones = direcciones::all();

        return view('/administrador/tutores', compact('tutores','personas'));
    }

    public function addProfesores(Request $request){
        //fechaFormateada
        $fechaFormateada = date('ymd', strtotime($request->fechaNacimiento));
        //Contraseña generada
        $contrasenia = Str::random(8);
        //Creacion de usuario
        $usuario = new usuarios();
        $usuario -> usuario = strtolower(substr($request->apellidoPaterno,0,2) . substr($request->apellidoMaterno,0,1) . substr($request->nombres,0,1) . $fechaFormateada . Str::random(3));
        $usuario -> contrasenia = $contrasenia;//Hash::make($contrasenia);
        $usuario -> activo = 1;
        //echo "Tu contraseña generada es: $contrasenia";
        //return $usuario -> contrasenia . " " . Hash::check($contrasenia,$usuario -> contrasenia);
        $usuario -> save();
        
        //Se busca el tipo de usuario en la BD
        $tipoUsuario = tipoUsuarios::where('tipoUsuario', 'profesor')->first();
        
        $usuarioTipoUsuario = new usuarios_tiposUsuarios();
        $usuarioTipoUsuario -> idUsuario = $usuario -> id;
        $usuarioTipoUsuario -> idTipoUsuario = $tipoUsuario -> idTipoUsuario;
        $usuarioTipoUsuario -> save();

        $persona = new personas();
        $persona -> nombre = $request -> nombres;
        $persona -> apellidoP = $request -> apellidoPaterno;
        $persona -> apellidoM = $request -> apellidoMaterno;
        $persona -> fechaNacimiento = $request -> fechaNacimiento;
        $persona -> idUsuario = $usuario -> id;
        $persona -> save();
        
        $profesor = new profesores();
        $profesor -> correoElectronico = $request -> email;
        $profesor -> numTelefono = $request -> telefono;
        $profesor -> idPersona = $persona -> id;
        $profesor -> activo = 1;
        $profesor -> save();

        return redirect()->route('admin.profesores');
    }

    public function addMaterias(Request $request){
        $materia = new materias();
        $materia -> materia = $request -> nombre_materia;
        $materia -> descripcion = $request -> descripcion;
        $materia -> activo = 1;
        $materia -> extracurricular = $request -> extracurricular;

        $materia -> save();

        return redirect()->route('admin.materias');
    }
}