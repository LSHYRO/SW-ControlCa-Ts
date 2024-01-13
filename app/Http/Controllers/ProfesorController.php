<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index()
{
    //return "Hola profesor";
    return view('administrador.profesores');
    // Lógica para mostrar la lista de profesores
}

public function create()
{
    // Lógica para mostrar el formulario de creación de profesor
}

public function show($id)
{
    // Lógica para mostrar un profesor específico
}

public function edit($id)
{
    // Lógica para mostrar el formulario de edición de profesor
}

public function update(Request $request, $id)
{
    // Lógica para actualizar un profesor
}

}
