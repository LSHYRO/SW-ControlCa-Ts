<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Exception;
use Inertia\Inertia;

class LoginController extends Controller{

    public function Login()
    {
        return Inertia::render('Login/InicioSesion');
    }

}