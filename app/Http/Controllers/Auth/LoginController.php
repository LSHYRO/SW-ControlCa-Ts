<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\usuarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $usuario = usuarios::where('idUsuario', auth()->user()->idUsuario)->with(['tipoUsuarios'])->get();
            $tipoUsuario = $usuario[0]->tipoUsuarios->tipoUsuario;
            switch ($tipoUsuario) {
                case "administrador":
                    return redirect()->route('admin.inicio');
                    break;
                case "director":
                    return redirect()->route('director.inicio');
                    break;
                case "directivo":
                    return redirect()->route('secre.inicio');
                    break;
                case "profesor":
                    return redirect()->route('profe.inicio');
                    break;
                case "estudiante":
                    return redirect()->route('alumno.inicio');
                    break;
                case "tutor":
                    return redirect()->route('tutor.inicio');
                    break;
            }
        }
        return Inertia::render('Login/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'usuario' => ['required'],
                'password' => ['required'],
            ]);

            $remember = $request->remember;

            $user = usuarios::where('usuario', $request->usuario)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $remember);
                $request->session()->regenerate();

                $usuario = usuarios::where('idUsuario', auth()->user()->idUsuario)->with(['tipoUsuarios'])->get();
                $tipoUsuario = $usuario[0]->tipoUsuarios->tipoUsuario;
                switch ($tipoUsuario) {
                    case "administrador":
                        return redirect()->intended(route('admin.inicio'));
                        break;
                        case "director":
                            return redirect()->intended(route('director.inicio'));
                            break;
                        case "directivo":
                            return redirect()->intended(route('secre.inicio'));
                            break;
                        case "profesor":
                            return redirect()->intended(route('profe.inicio'));
                            break;
                        case "estudiante":
                            return redirect()->intended(route('alumno.inicio'));
                            break;
                        case "tutor":
                            return redirect()->intended(route('tutor.inicio'));
                            break;
                }
            }

            return back()->with(['message' => 'Las credenciales son incorrectas', 'color' => 'red']);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('inicioSesion');
    }
}
