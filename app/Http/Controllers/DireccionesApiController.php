<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DireccionesApiController extends Controller
{
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
    /*
    public function consultarAsentamEstMun($estado, $municipio){
        $codPostales =  Http::get('https://apicp.softfortoday.com/api/v1/codigos_postales/clave_municipio'.$estado.'/'.$municipio);
        $codPost = $codPostales->codigos_postales;
        $asentamientos = [];

        foreach ($codPostales as $codPostal) {
            $asentamientos = array_merge($asentamientos, $this->consultarAsentamCodP($codPostal->clave_postal));
        }
        return $asentamientos;

    }
    */
}
