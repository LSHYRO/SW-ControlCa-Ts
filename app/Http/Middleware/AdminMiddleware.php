<?php

namespace App\Http\Middleware;

use App\Models\usuarios;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check()) {
            $usuario = usuarios::where('idUsuario', auth()->user()->idUsuario)->with(['tipoUsuarios'])->get();                     
            $tipoUsuario = $usuario[0]->tipoUsuarios->tipoUsuario;
            if ($tipoUsuario === "administrador") {
                return $next($request);
            }

        }
        return redirect()->route('inicioSesion');
    }
}
