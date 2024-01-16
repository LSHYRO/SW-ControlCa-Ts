<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
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

Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'index')->name('inicioSesion');
    Route::post('/', 'login');
    Route::get('/logout','logout')->name('cerrarSesion');
});

Route::middleware(['adminS'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/administrador', 'inicio')->name('admin.inicio');
        Route::get('/administrador/profesores', 'profesores')->name('admin.profesores');

        Route::get('/administrador/tutores_alumnos', 'tutores_alumnos')->name('admin.tutoresAlum');

        Route::get('/administrador/alumnos', 'alumnos')->name('admin.alumnos');
        Route::get('/administrador/directivos', 'directivos')->name('admin.directivos');
        Route::get('/administrador/materias', 'materias')->name('admin.materias');
        Route::get('/administrador/clases', 'clases')->name('admin.clases');
        Route::get('/administrador/gradosgrupos', 'gradosgrupos')->name('admin.gradosgrupos');
        Route::get('/administrador/ciclosperiodos', 'ciclosperiodos')->name('admin.ciclosperiodos');
        Route::get('/administrador/obtenerciclos', 'obtenerciclos')->name('admin.obtenerciclos');

        Route::post('/administrador/profesores', 'addProfesores')->name('admin.addProfesores');
        Route::post('/administrador/directivos', 'addDirectivos')->name('admin.addDirectivos');
        Route::post('/administrador/materias', 'addMaterias')->name('admin.addMaterias');
        Route::post('/administrador/tutores', 'addTutores')->name('admin.addTutores');
        Route::post('/administrador/alumnos', 'agregarAlumno')->name('admin.addAlumnos');
        Route::post('/administrador/grados', 'addGrados')->name('admin.addGrados');
        Route::post('/administrador/grupos', 'addGrupos')->name('admin.addGrupos');
        Route::post('/administrador/ciclos', 'addCiclos')->name('admin.addCiclos');
        Route::post('/administrador/periodos', 'addPeriodos')->name('admin.addPeriodos');
        Route::post('/administrador/clases', 'addClases')->name('admin.addClases');


        Route::get('/administrador/admin/buscar/tutor', 'buscarTutor')->name('ad.busquedaTutor');

        Route::delete('/administrador/alumnos/delete/{alumnosIds}', 'eliminarAlumnos')->name('admin.elimAlumnos');
        Route::delete('/administrador/alumnos/{idAlumno}', 'eliminarAlumno')->name('admin.eliminarAlumno');
        Route::put('/administrador/alumnos/{idAlumno}/edit', 'actualizarAlumno')->name('admin.actualizarAlumno');

        Route::delete('/administrador/profesores/delete/{personalIds}', 'elimProfesores')->name('admin.elimProfesores');

        Route::delete('/administrador/profesores/{idPersonal}', 'eliminarProfesores')->name('admin.eliminarProfesores');
        Route::put('/administrador/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('admin.actualizarProfesores');

        Route::delete('/administrador/directivos/{idPersonal}', 'eliminarDirectivos')->name('admin.eliminarDirectivos');
        Route::put('/administrador/directivos/{idPersonal}/edit', 'actualizarDirectivo')->name('admin.actualizarDirectivos');

        Route::delete('/administrador/materias/delete/{materiasIds}', 'elimMaterias')->name('admin.elimMaterias');

        Route::delete('/administrador/materias/{idMateria}', 'eliminarMaterias')->name('admin.eliminarMaterias');
        Route::put('/administrador/materias/{idMateria}/edit', 'actualizarMateria')->name('admin.actualizarMaterias');

        Route::delete('/administrador/tutores/delete/{tutoresIds}', 'elimTutores')->name('admin.elimTutores');
        Route::delete('/administrador/tutores/{idTutor}', 'eliminarTutor')->name('admin.eliminarTutor');
        Route::put('/administrador/tutores/{idTutor}/edit', 'actualizarTutor')->name('admin.actualizarTutor');

        Route::delete('/administrador/clases/{idClase}', 'eliminarClases')->name('admin.eliminarClases');
        Route::delete('/administrador/clases/delete/{clasesIds}', 'elimClases')->name('admin.elimClases');
        Route::put('/administrador/clases/{idClase}/edit', 'actualizarClases')->name('admin.actualizarClases');

        Route::delete('/administrador/grados/{idGrado}', 'eliminarGrados')->name('admin.eliminarGrados');
        Route::put('/administrador/grados/{idGrado}/edit', 'actualizarGrados')->name('admin.actualizarGrados');
        Route::delete('/administrador/grados/delete/{gradosIds}', 'elimGrados')->name('admin.elimGrados');

        Route::delete('/administrador/grupos/{idGrupo}', 'eliminarGrupos')->name('admin.eliminarGrupos');
        Route::put('/administrador/grupos/{idGrupo}/edit', 'actualizarGrupos')->name('admin.actualizarGrupos');
        Route::delete('/administrador/grupos/delete/{gruposIds}', 'elimGrupos')->name('admin.elimGrupos');

        Route::delete('/administrador/ciclos/{idCiclo}', 'eliminarCiclos')->name('admin.eliminarCiclos');
        Route::put('/administrador/ciclos/{idCiclo}/edit', 'actualizarCiclos')->name('admin.actualizarCiclos');
        Route::delete('/administrador/ciclos/delete/{ciclosIds}', 'elimCiclos')->name('admin.elimCiclos');

        Route::delete('/administrador/periodos/{idPeriodo}', 'eliminarPeriodos')->name('admin.eliminarPeriodos');
        Route::put('/administrador/periodos/{idPeriodo}/edit', 'actualizarPeriodos')->name('admin.actualizarPeriodos');
        Route::delete('/administrador/periodos/delete/{periodosIds}', 'elimPeriodos')->name('admin.elimPeriodos');

        //
        Route::get('obtener/datos/grupos/xgrados/{idGrado}', 'obtenerGruposXGrado')->name('ad.gradosXgrupos');
    });
});

//Rutas para obtener los estados, municipios, asentamientos y codigos postales
Route::controller(DireccionesApiController::class)->group(function () {
    // Ruta para obtener todos los estados
    Route::get('obtener/estados', 'consultarEstados')->name('consEstados');
    // Ruta para obtener todos los ciclos
    Route::get('obtener/ciclos', 'consultarCiclos')->name('consCiclos');

    // Rutas para encontrar Estados, municipios, asentamientos por codigo postal
    Route::get('obtener/estado/codigoPostal/{codigoPostal}', 'obtenerEstadoPorCodigoPostal')->name('consEstadoXCodPostal');
    Route::get('obtener/municipios/codigoPostal/{codigoPostal}', 'obtenerMunicipiosPorCodigoPostal')->name('consMunicipiosXCodPostal');
    Route::get('obtener/asentamientos/codigoPostal/{codigoPostal}', 'obtenerAsentamientosPorCodigoPostal')->name('consAsentamientosXCodPostal');

    //Rutas para encotrar municipios y asentamientos por el codigo de estado y municipios respectivamente
    Route::get('obtener/municipios/idEstado/{idEstado}', 'obtenerMunicipiosPorEstado')->name('consMunicipiosXIdEstado');
    Route::get('obtener/asentamientos/idMunicipio/{idMunicipio}', 'obtenerAsentamientosPorMunicipio')->name('consAsentamientosXIdMunicipio');

    //Ruta para datos con codigo postal
    Route::get('obtener/datos/estado/municipio/asentamientos/{codigoPostal}', 'consDatosPorCodigoPostal')->name('consDatosXCodigoPostal');

    //
    Route::get('obtener/datos/asentamiento/{idAsentamiento}', 'informacionAsentamiento')->name('infoAsentamiento');


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
