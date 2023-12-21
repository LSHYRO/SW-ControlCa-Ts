<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\asentamientos;
use App\Models\codigoPostal;
use App\Models\estados;
use App\Models\municipios;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DireccionesApiController extends Controller
{

    //Obtencion de Estados, municipios, asentamientos y codigos postales
    public function consultarEstados()
    {
        $estados = estados::all();
        return $estados;
    }

    public function consultarAsentamientos()
    {
        $asentamientos = asentamientos::all();
        return $asentamientos;
    }

    public function consultarMunicipios()
    {
        $municipios = municipios::all();
        return $municipios;
    }

    public function consultarCodigoPostal()
    {
        $codigosPostales = codigoPostal::all();
        return $codigosPostales;
    }
    //----------------------------------------------------------------
    // Obtencion de estados, municipios y asentamientos por codigo postal
    public function obtenerEstadoPorCodigoPostal($codigoPostal)
    {
        try {
            $estado = asentamientos::whereHas('codigoPostal', function ($query) use ($codigoPostal) {
                $query->where('codigoPostal', $codigoPostal);
            })->firstOrFail()->municipios->estados;

            return response()->json($estado);
        } catch (ModelNotFoundException $e) {
            return response();
        }
    }

    public function obtenerMunicipiosPorCodigoPostal($codigoPostal)
    {
        $municipios = codigoPostal::where('codigoPostal', $codigoPostal)
            ->firstOrFail()
            ->asentamientos()
            ->with('municipios')
            ->get()
            ->pluck('municipios')
            ->unique();

        return response()->json($municipios);
    }

    public function obtenerAsentamientosPorCodigoPostal($codigoPostal)
    {
        $asentamientos = codigoPostal::where('codigoPostal', $codigoPostal)
            ->firstOrFail()
            ->asentamientos;

        return response()->json($asentamientos);
    }
    //----------------------------------------------------------------
    // Obtener Estados, municipios y asentamientos por id de forma individual
    public function obtenerMunicipiosPorEstado($idEstado)
    {
        $estado = estados::with('municipios')->find($idEstado);

        if (!$estado) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        }

        $municipios = $estado->municipios->sortBy('municipio')->values()->all();

        return response()->json($municipios);
    }

    public function obtenerAsentamientosPorMunicipio($idMunicipio)
    {
        $municipio = municipios::with('asentamientos')->find($idMunicipio);

        if (!$municipio) {
            return response()->json(['message' => 'Municipio no encontrado'], 404);
        }

        $asentamientos = $municipio->asentamientos->sortBy('asentamiento')->values()->all();

        return response()->json($asentamientos);
    }

    // ----------------------------------------------------------------
    //Obtener todos los elementos anteriores por codPostal

    public function consDatosPorCodigoPostal($codigoPostal)
    {
        $datos = [];

        // Buscar el asentamiento por código postal
        $codigoPostalModel = codigoPostal::where('codigoPostal', $codigoPostal)->first();
        
        if ($codigoPostalModel) {
            // Obtener el primer asentamiento asociado al código postal
            $asentamiento = $codigoPostalModel->asentamientos->first();
            
            if ($asentamiento) {
                // Acceder al municipio a través de la relación en el modelo Asentamientos
                $municipio = $asentamiento->municipios;
                $datos['municipio'] = $municipio;
                if ($municipio) {
                    // Acceder al estado a través de la relación en el modelo Municipios
                    $estado = $municipio->estados;
                    
                    
                    if ($estado) {
                        $datos['estado'] = $estado;

                        // Puedes agregar lógica para obtener otros datos según sea necesario
                    }
                }
            }
        }

        return response()->json($datos);
    }




    /*
    public function consultarCodPostal($codigo){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/codigos_postales/'.$codigo);
        return $response -> json();
    }

    //Consulta a traves del codigo postal los asentamientos 
    public function consultarAsentamCodP($codigo){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/asentamientos/codigo_postal/'.$codigo);
        return $response -> json();
    }
    //Consulta los estados de forma general
    public function consultarEstados(){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/estados');
        return $response -> json();
    }
    //Consulta los estados a través de los codigos postales
    public function consultarEstadosCodP($codigo){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/estados/codigo_postal/'.$codigo);
        return $response -> json();
    }

    public function consultarMunicipios($estado){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/municipios/nombre_estado/'.$estado);
        return $response -> json();
    }
    
    */
}
