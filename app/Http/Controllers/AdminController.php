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
}