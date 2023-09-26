<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __invoke()
    {
        //return "Bienvenido a la pagina principal";
        return view('/administrador/inicio');
    }
}
