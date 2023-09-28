<?php

namespace App\Http\Controllers;

use App\Models\personas;
use App\Models\profesores;
use App\Models\usuarios;
use Illuminate\Http\Request;
use App\Models\alumnos;

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
}
