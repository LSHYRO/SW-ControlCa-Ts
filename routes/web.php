<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ProfeController;
use App\Http\Controllers\SecreController;
use App\Http\Controllers\TutorController;
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
Route::controller(LoginController::class)->group(function (){
    Route::get('/is', 'Login')->name('inicioSesion');
});

Route::controller(AlumnoController::class)->group(function (){
    Route::get('/', 'inicio')->name('alumno.inicio');
});

Route::controller(DirectorController::class)->group(function (){
    Route::get('/director', 'inicio')->name('director.inicio');
    
    Route::get('/director/profesores', 'profesores')->name('director.profesores');

    Route::get('/director/tutores_alumnos', 'tutores_alumnos')->name('director.tutoresAlum');

    Route::get('/director/alumnos', 'alumnos')->name('director.alumnos');
    Route::get('/director/directivos', 'directivos')->name('director.directivos');    
    Route::get('/director/materias', 'materias')->name('director.materias');
    Route::get('/director/clases', 'clases')->name('director.clases');
    Route::get('/director/calificaciones', 'calificaciones')->name('director.calificaciones');
    Route::get('/director/gradosgrupos', 'gradosgrupos')->name('director.gradosgrupos');
    Route::get('/director/ciclosperiodos', 'ciclosperiodos')->name('director.ciclosperiodos');
    Route::get('/director/obtenerciclos', 'obtenerciclos')->name('director.obtenerciclos');

    Route::post('/director/profesores', 'addProfesores')->name('director.addProfesores');
    Route::post('/director/directivos', 'addDirectivos')->name('director.addDirectivos');
    Route::post('/director/materias', 'addMaterias')->name('director.addMaterias');
    Route::post('/director/tutores', 'addTutores')->name('director.addTutores');
    Route::post('/director/alumnos', 'agregarAlumno')->name('director.addAlumnos');
    Route::post('/director/grados', 'addGrados')->name('director.addGrados');
    Route::post('/director/grupos', 'addGrupos')->name('director.addGrupos');
    Route::post('/director/ciclos', 'addCiclos')->name('director.addCiclos');
    Route::post('/director/periodos', 'addPeriodos')->name('director.addPeriodos');
    Route::post('/director/clases', 'addClases')->name('director.addClases');

    Route::get('/director/buscar/tutor', 'buscarTutor')->name('director.busquedaTutor');

    Route::delete('/director/alumnos/delete/{alumnosIds}', 'eliminarAlumnos')->name('director.elimAlumnos');
    Route::delete('/director/alumnos/{idAlumno}', 'eliminarAlumno')->name('director.eliminarAlumno');
    Route::put('/director/alumnos/{idAlumno}/edit', 'actualizarAlumno')->name('director.actualizarAlumno');
    
    Route::delete('/director/profesores/delete/{personalIds}', 'elimProfesores')->name('director.elimProfesores');

    Route::delete('/director/profesores/{idPersonal}', 'eliminarProfesores')->name('director.eliminarProfesores');
    Route::put('/director/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('director.actualizarProfesores');

    Route::delete('/director/directivos/{idPersonal}', 'eliminarDirectivos')->name('director.eliminarDirectivos');
    Route::put('/director/directivos/{idPersonal}/edit', 'actualizarDirectivo')->name('director.actualizarDirectivos');

    Route::delete('/director/materias/delete/{materiasIds}', 'elimMaterias')->name('director.elimMaterias');

    Route::delete('/director/materias/{idMateria}', 'eliminarMaterias')->name('director.eliminarMaterias');
    Route::put('/director/materias/{idMateria}/edit', 'actualizarMateria')->name('director.actualizarMaterias');
    
    Route::delete('/director/tutores/delete/{tutoresIds}', 'elimTutores')->name('director.elimTutores');
    Route::delete('/director/tutores/{idTutor}', 'eliminarTutor')->name('director.eliminarTutor');
    Route::put('/director/tutores/{idTutor}/edit', 'actualizarTutor')->name('director.actualizarTutor');

    Route::delete('/director/clases/{idClase}', 'eliminarClases')->name('director.eliminarClases');
    Route::delete('/director/clases/delete/{clasesIds}', 'elimClases')->name('director.elimClases');
    Route::put('/director/clases/{idClase}/edit', 'actualizarClases')->name('director.actualizarClases');

    Route::delete('/director/grados/{idGrado}', 'eliminarGrados')->name('director.eliminarGrados');
    Route::put('/director/grados/{idGrado}/edit', 'actualizarGrados')->name('director.actualizarGrados');
    Route::delete('/director/grados/delete/{gradosIds}', 'elimGrados')->name('director.elimGrados');

    Route::delete('/director/grupos/{idGrupo}', 'eliminarGrupos')->name('director.eliminarGrupos');
    Route::put('/director/grupos/{idGrupo}/edit', 'actualizarGrupos')->name('director.actualizarGrupos');
    Route::delete('/director/grupos/delete/{gruposIds}', 'elimGrupos')->name('director.elimGrupos');

    Route::delete('/director/ciclos/{idCiclo}', 'eliminarCiclos')->name('director.eliminarCiclos');
    Route::put('/director/ciclos/{idCiclo}/edit', 'actualizarCiclos')->name('director.actualizarCiclos');
    Route::delete('/director/ciclos/delete/{ciclosIds}', 'elimCiclos')->name('director.elimCiclos');

    Route::delete('/director/periodos/{idPeriodo}', 'eliminarPeriodos')->name('director.eliminarPeriodos');
    Route::put('/director/periodos/{idPeriodo}/edit', 'actualizarPeriodos')->name('director.actualizarPeriodos');
    Route::delete('/director/periodos/delete/{periodosIds}', 'elimPeriodos')->name('director.elimPeriodos');

    Route::get('/director/obtener/datos/grupos/xgrados/{idGrado}', 'obtenerGruposXGrado')->name('director.gradosXgrupos');
   
});

Route::controller(ProfeController::class)->group(function (){
    Route::get('/pofesor', 'inicio')->name('profe.inicio');
});

Route::controller(SecreController::class)->group(function (){
    Route::get('/', 'inicio')->name('secre.inicio');
});

Route::controller(TutorController::class)->group(function (){
    Route::get('/', 'inicio')->name('tutor.inicio');
});

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
    Route::get('/usuarios', 'usuarios')->name('admin.usuarios');

    Route::post('/profesores', 'addProfesores')->name('admin.addProfesores');
    Route::post('/directivos', 'addDirectivos')->name('admin.addDirectivos');
    Route::post('/materias', 'addMaterias')->name('admin.addMaterias');
    Route::post('/tutores', 'addTutores')->name('admin.addTutores');
    Route::post('/alumnos', 'agregarAlumno')->name('admin.addAlumnos');
    Route::post('/grados', 'addGrados')->name('admin.addGrados');
    Route::post('/grupos', 'addGrupos')->name('admin.addGrupos');
    Route::post('/ciclos', 'addCiclos')->name('admin.addCiclos');
    Route::post('/periodos', 'addPeriodos')->name('admin.addPeriodos');
    Route::post('/clases', 'addClases')->name('admin.addClases');
    Route::post('/usuarios', 'addUsuarios')->name('admin.addUsuarios');


    Route::get('/admin/buscar/tutor', 'buscarTutor')->name('ad.busquedaTutor');

    Route::delete('/alumnos/delete/{alumnosIds}', 'eliminarAlumnos')->name('admin.elimAlumnos');
    Route::delete('/alumnos/{idAlumno}', 'eliminarAlumno')->name('admin.eliminarAlumno');
    Route::put('/alumnos/{idAlumno}/edit', 'actualizarAlumno')->name('admin.actualizarAlumno');
    
    Route::delete('/profesores/delete/{personalIds}', 'elimProfesores')->name('admin.elimProfesores');

    Route::delete('/profesores/{idPersonal}', 'eliminarProfesores')->name('admin.eliminarProfesores');
    Route::put('/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('admin.actualizarProfesores');

    Route::delete('/directivos/{idPersonal}', 'eliminarDirectivos')->name('admin.eliminarDirectivos');
    Route::put('/directivos/{idPersonal}/edit', 'actualizarDirectivo')->name('admin.actualizarDirectivos');

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

    Route::delete('/usuarios/{idUsuario}', 'eliminarUsuarios')->name('admin.eliminarUsuarios');
    Route::put('/usuarios/{idUsuario}/edit', 'actualizarUsuarios')->name('admin.actualizarUsuarios');
    Route::delete('/usuarios/delete/{usuariosIds}', 'elimUsuarios')->name('admin.elimUsuarios');

    Route::get('obtener/datos/grupos/xgrados/{idGrado}', 'obtenerGruposXGrado')->name('ad.gradosXgrupos');
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