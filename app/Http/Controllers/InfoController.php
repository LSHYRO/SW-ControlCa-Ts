<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\tipoUsuarios;
use App\Models\usuarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InfoController extends Controller
{
    public function obtenerUsuario()
    {
        return auth()->user();
    }

    public function obtenerTipoUsuario($idTipoUsuario)
    {
        return tipoUsuarios::find($idTipoUsuario);
    }

    public function obtenerDatosPersonal($idUsuario)
    {
        try {
            $usuario = usuarios::where('idUsuario', $idUsuario)->with(['personal'])->first();
            return $usuario->personal;
        } catch (Exception $e) {
            Log::info($e);
            return ['personal'=>'Sin asignar'];
        }
    }
}
