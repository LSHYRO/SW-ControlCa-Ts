<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DireccionesApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::controller(AdminController::class)->group(function () {
    Route::get('/a', 'index')->name('admin.principal');
    Route::get('/', 'inicio')->name('admin.inicio');
    Route::get('/profesores', 'profesores')->name('admin.profesores');

    Route::get('/tutores_alumnos', 'tutores_alumnos')->name('admin.tutoresAlum');

    Route::get('/alumnos', 'alumnos')->name('admin.alumnos');
    Route::get('/directivos', 'directivos')->name('admin.directivos');    
    Route::get('/materias', 'materias')->name('admin.materias');
    Route::get('/clases', 'clases')->name('admin.clases');
    Route::get('/gradosgrupos', 'gradosgrupos')->name('admin.gradosgrupos');
    Route::get('/ciclosperiodos', 'ciclosperiodos')->name('admin.ciclosperiodos');
    Route::get('/obtenerciclos', 'obtenerciclos')->name('admin.obtenerciclos');

    Route::post('/profesores', 'addProfesores')->name('admin.addProfesores');
    Route::post('/materias', 'addMaterias')->name('admin.addMaterias');
    Route::post('/tutores', 'addTutores')->name('admin.addTutores');
    Route::post('/grados', 'addGrados')->name('admin.addGrados');
    Route::post('/grupos', 'addGrupos')->name('admin.addGrupos');
    Route::post('/ciclos', 'addCiclos')->name('admin.addCiclos');
    Route::post('/periodos', 'addPeriodos')->name('admin.addPeriodos');
    Route::post('/clases', 'addClases')->name('admin.addClases');


    Route::get('/admin/search', 'buscarT')->name('ad.busquedaTutor');
    
    Route::delete('/profesores/delete/{personalIds}', 'elimProfesores')->name('admin.elimProfesores');

    Route::delete('/profesores/{idPersonal}', 'eliminarProfesores')->name('admin.eliminarProfesores');
    Route::put('/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('admin.actualizarProfesores');

    Route::delete('/materias/delete/{materiasIds}', 'elimMaterias')->name('admin.elimMaterias');

    Route::delete('/materias/{idMateria}', 'eliminarMaterias')->name('admin.eliminarMaterias');
    Route::put('/materias/{idMateria}/edit', 'actualizarMateria')->name('admin.actualizarMaterias');
    
    Route::delete('/tutores/delete/{tutoresIds}', 'elimTutores')->name('admin.elimTutores');
    Route::delete('/tutores/{idTutor}', 'eliminarTutor')->name('admin.eliminarTutor');
    Route::put('/tutores/{idTutor}/edit', 'actualizarTutor')->name('admin.actualizarTutor');

    Route::delete('/clases/{idClase}', 'eliminarClases')->name('admin.eliminarClases');
    Route::delete('/clases/delete/{clasesIds}', 'elimClases')->name('admin.elimClases');
    Route::put('/clases/{idClase}/edit', 'actualizarClases')->name('admin.actualizarClases');

    Route::delete('/grados/{idGrado}', 'eliminarGrados')->name('admin.eliminarGrados');
    Route::put('/grados/{idGrado}/edit', 'actualizarGrados')->name('admin.actualizarGrados');
    Route::delete('/grados/delete/{gradosIds}', 'elimGrados')->name('admin.elimGrados');

    Route::delete('/grupos/{idGrupo}', 'eliminarGrupos')->name('admin.eliminarGrupos');
    Route::put('/grupos/{idGrupo}/edit', 'actualizarGrupos')->name('admin.actualizarGrupos');
    Route::delete('/grupos/delete/{gruposIds}', 'elimGrupos')->name('admin.elimGrupos');

    Route::delete('/ciclos/{idCiclo}', 'eliminarCiclos')->name('admin.eliminarCiclos');
    Route::put('/ciclos/{idCiclo}/edit', 'actualizarCiclos')->name('admin.actualizarCiclos');
    Route::delete('/ciclos/delete/{ciclosIds}', 'elimCiclos')->name('admin.elimCiclos');

    Route::delete('/periodos/{idPeriodo}', 'eliminarPeriodos')->name('admin.eliminarPeriodos');
    Route::put('/periodos/{idPeriodo}/edit', 'actualizarPeriodos')->name('admin.actualizarPeriodos');
    Route::delete('/periodos/delete/{periodosIds}', 'elimPeriodos')->name('admin.elimPeriodos');
});

//Rutas para obtener los estados, municipios, asentamientos y codigos postales
Route::controller(DireccionesApiController::class)->group(function () {
    // Ruta para obtener todos los estados
    Route::get('obtener/estados','consultarEstados')->name('consEstados');
    // Ruta para obtener todos los ciclos
    Route::get('obtener/ciclos','consultarCiclos')->name('consCiclos');

    // Rutas para encontrar Estados, municipios, asentamientos por codigo postal
    Route::get('obtener/estado/codigoPostal/{codigoPostal}','obtenerEstadoPorCodigoPostal')->name('consEstadoXCodPostal');
    Route::get('obtener/municipios/codigoPostal/{codigoPostal}','obtenerMunicipiosPorCodigoPostal')->name('consMunicipiosXCodPostal');
    Route::get('obtener/asentamientos/codigoPostal/{codigoPostal}','obtenerAsentamientosPorCodigoPostal')->name('consAsentamientosXCodPostal');

    //Rutas para encotrar municipios y asentamientos por el codigo de estado y municipios respectivamente
    Route::get('obtener/municipios/idEstado/{idEstado}','obtenerMunicipiosPorEstado')->name('consMunicipiosXIdEstado');
    Route::get('obtener/asentamientos/idMunicipio/{idMunicipio}','obtenerAsentamientosPorMunicipio')->name('consAsentamientosXIdMunicipio');
    
    //Ruta para datos con codigo postal
    Route::get('obtener/datos/estado/municipio/asentamientos/{codigoPostal}', 'consDatosPorCodigoPostal')->name('consDatosXCodigoPostal');
    /*
    //Se obtienen datos a partir del codigo postal 
    Route::get('/obtener/codPostal/{codigo}', 'consultarCodPostal')->name('consultaCodPostal');
    Route::get('/obtener/asentamiento/codPostal/{codigo}', 'consultarAsentamCodP')->name('consultaCodPos');
    Route::get('/obtener/estados', 'consultarEstados')->name('consultarEstados');
    Route::get('/obtener/estados/codPostal/{codigo}', 'consultarEstadosCodP')->name('consultarEstadosCodP');
    Route::get('/obtener/municipios/{estado}', 'consultarMunicipios')->name('consultarMunicipios');
    */
});


/* 
Route::get('/', function () {
    return Inertia::render('Principal',[
        'ad.profesores' => Route::has('Admin/Profesores')
    ]);
});

Route::get('/profesor', function () {
    return Inertia::render('Admin/Profesores');
})->name('adm.prof');
*/

/*
Route::get('/f', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
*/