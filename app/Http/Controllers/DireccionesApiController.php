<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DireccionesApiController extends Controller
{
    //Consulta a traves del codigo postal
    public function consultarCodPostal($codigo){
        $response = Http::get('https://apicp.softfortoday.com/api/v1/asentamientos/codigo_postal/'.$codigo);
        
        return $response -> json();
    }
}
