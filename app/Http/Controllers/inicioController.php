<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class inicioController extends Controller
{
    //Principal
    public function index(){
        return view('administrador.inicio');
    }
    //Crear
    public function create(){
        return "Aqui creas un curso";
    }
    //Mostrar
    public function show($curso){
        return "Bienvenido al curso $curso";
    }
}
