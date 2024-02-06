<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ProfeController;
use App\Http\Controllers\SecreController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DireccionesApiController;
use App\Http\Controllers\InfoController;
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
    Route::get('/logout', 'logout')->name('cerrarSesion');
    Route::post('/register', 'register')->name('registrarse');
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
        Route::get('/administrador/usuarios', 'usuarios')->name('admin.usuarios');

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
        Route::post('/administrador/usuarios', 'addUsuarios')->name('admin.addUsuarios');


        Route::get('/administrador/admin/buscar/tutor', 'buscarTutor')->name('ad.busquedaTutor');

        Route::get('/administrador/contrasenia', 'perfil')->name('admin.contrasenia');
        Route::put('/administrador/contrasenia/actualizar/contraseña','actualizarContrasenia')->name('admin.actualizarContraseña');

        Route::delete('/administrador/alumnos/delete/{alumnosIds}', 'eliminarAlumnos')->name('admin.elimAlumnos');
        Route::delete('/administrador/alumnos/{idAlumno}', 'eliminarAlumno')->name('admin.eliminarAlumno');
        Route::put('/administrador/alumnos/{idAlumno}/edit', 'actualizarAlumno')->name('admin.actualizarAlumno');

        Route::delete('/administrador/profesores/delete/{personalIds}', 'elimProfesores')->name('admin.elimProfesores');

        Route::delete('/administrador/profesores/{idPersonal}', 'eliminarProfesores')->name('admin.eliminarProfesores');
        Route::put('/administrador/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('admin.actualizarProfesores');

        Route::delete('/administrador/directivos/{idPersonal}', 'eliminarDirectivos')->name('admin.eliminarDirectivos');
        Route::delete('/administrador/directivos/delete/{personalIds}', 'elimDirectivos')->name('admin.elimDirectivos');
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

        Route::put('/administrador/usuarios/restaurar-usuario/{idUsuario}', 'restaurarUsuario')->name('admin.restUsuario');
        Route::delete('/administrador/usuarios/delete/{usuariosIds}', 'elimUsuarios')->name('admin.elimUsuarios');
        Route::delete('/administrador/usuarios/{idUsuario}', 'eliminarUsuarios')->name('admin.eliminarUsuarios');
        Route::put('/administrador/usuarios/{idUsuario}/edit', 'actualizarUsuarios')->name('admin.actualizarUsuarios');
        
        

        Route::get('obtener/datos/ciclo/xgrados/{idGrado}', 'obtenerCicloXGrado')->name('ad.cicloXgrupos');
        Route::get('obtener/datos/grupos/xgrados/{idGrado}', 'obtenerGruposXGrado')->name('ad.gradosXgrupos');
        
    });
});

Route::controller(InfoController::class)->group(
    function () {
        Route::get('obtener/info/usuario/login', 'obtenerUsuario')->name('obtenerUsuario');
        Route::get('obtener/info/tipoUsuario/{idTipoUsuario}', 'obtenerTipoUsuario')->name('obtenerTipoUsuario');
        Route::get('obtener/info/personal/datos/{idUsuario}', 'obtenerDatosPersonal')->name('obtenerDatoPersona');
    }
);

Route::controller(AlumnoController::class)->group(function () {
    Route::get('/alumno', 'inicio')->name('alumno.inicio');
    //Route::get('/alumno/clasesA', 'clases')->name('alumno.clases');
    Route::get('/alumno/perfil', 'perfil')->name('alumno.perfil');
    Route::put('/alumno/perfil/actualizar/contraseña','actualizarContrasenia')->name('alumno.actualizarContraseña');
    Route::get('/alumno/clasesAlumno', 'clases')->name('alumno.enCurso');
    Route::get('/profesor/personal/obtener', 'obtenerAlumno')->name('obtenerAlumno');
    Route::get('/alumno/clasesA/clase/{idClase}', 'mostrarClase')->name('alumno.mostrarClase'); 
    Route::get('/alumno/clasesA/obtener/datos/{idAlumno}', 'obtenerDatosClase')->name('obtenerDatosClaseA');
    Route::get('/alumno/calificaciones/obtener/datos/{idAlumno}', 'obtenerDatosCalificaciones')->name('alumno.obtenerDatosCalificaciones');
    Route::get('/alumno/materiasA/obtener/datos/{idClase}', 'obtenerDatosMateria')->name('obtenerDatosMateriaA');
    Route::get('/alumno/gradosA/obtener/datos/{idClase}', 'obtenerDatosGrado')->name('obtenerDatosGradoA');
    Route::get('/alumno/gruposA/obtener/datos/{idClase}', 'obtenerDatosGrupo')->name('obtenerDatosGrupoA');
    Route::get('/alumno/class/clase/{idClase}/cal-per/{idPeriodo}', 'mostrarCaliPer')->name('alumno.mostrarCalPeriodos');
    Route::get('/alumno/clas/clase/{idClase}/ver/cal-final-clase', 'mostrarCalFinalClase')->name('alumno.mostrarCalFinal');
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

    Route::get('obtener/datos/asentamiento/{idAsentamiento}', 'informacionAsentamiento')->name('infoAsentamiento');
});

Route::middleware(['directorS'])->group(function () {
    Route::controller(DirectorController::class)->group(function () {
        Route::get('/director', 'inicio')->name('director.inicio');

        Route::get('/director/profesores', 'profesores')->name('director.profesores');

        Route::get('/director/tutores_alumnos', 'tutores_alumnos')->name('director.tutoresAlum');

        Route::get('/director/alumnos', 'alumnos')->name('director.alumnos');
        Route::get('/director/directivos', 'directivos')->name('director.directivos');
        Route::get('/director/materias', 'materias')->name('director.materias');
        Route::get('/director/clases', 'clases')->name('director.clases');
        Route::get('/director/alumnosclases', 'alumnosclases')->name('director.alumnosclases');
        Route::get('/director/calificaciones', 'calificaciones')->name('director.calificaciones');
        Route::get('/director/gradosgrupos', 'gradosgrupos')->name('director.gradosgrupos');
        Route::get('/director/ciclosperiodos', 'ciclosperiodos')->name('director.ciclosperiodos');
        Route::get('/director/obtenerciclos', 'obtenerciclos')->name('director.obtenerciclos');
        Route::get('/director/cuentas', 'cuentas')->name('director.cuentas');

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
        Route::post('/director/cuentas', 'addCuentas')->name('director.addCuentas');
        Route::post('/director/alumnosclases', 'addAlumnosClases')->name('director.addAlumnosClases');

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

        Route::get('/director/perfil', 'perfil')->name('director.perfil');
        Route::put('/director/perfil/actualizar/contraseña','actualizarContrasenia')->name('director.actualizarContraseña');

        Route::get('/director/obtener/datos/clases/materiasxguposxgrados/{idClase}', 'obtenerClasesXMateriaGradoGrupo')->name('director.clasesXmateriasXgradosXgrupos');

        Route::put('/director/usuarios/restaurar-usuario/{idUsuario}', 'restaurarUsuario')->name('director.restUsuario');
        Route::delete('/director/cuentas/{idUsuario}', 'eliminarCuentas')->name('director.eliminarCuentas');
        Route::put('/director/cuentas/{idUsuario}/edit', 'actualizarCuentas')->name('director.actualizarCuentas');
        Route::delete('/director/cuentas/delete/{usuariosIds}', 'elimUsuarios')->name('director.elimCuentas');

        Route::delete('/director/alumnosclases/{idClase}', 'eliminarAlumnosClases')->name('director.eliminarAlumnosClases');
        Route::delete('/director/alumnosclases/delete/{clases_alumnosIds}', 'elimAlumnosClases')->name('director.elimAlumnosClases');
        //Route::put('/director/alumnosclases/{idClase}/edit', 'actualizarClases')->name('director.actualizarClases');

        Route::get('/director/obtener/datos/ciclo/xgrados/{idGrado}', 'obtenerCicloXGrado')->name('director.cicloXgrupos');
        Route::get('/director/obtener/datos/grupos/xgrados/{idGrado}', 'obtenerGruposXGrado')->name('director.gradosXgrupos');
        Route::get('/director/clasesAlumno/{idAlumno}', 'clasesDeAlumno')->name('director.verCalificaciones');
        Route::get('/director/clasesA/clase/{idClase}/alum/{idAlumno}', 'mostrarClase')->name('director.mostrarClase');
    });
});

Route::middleware(['profesorS'])->group(function () {
    Route::controller(ProfeController::class)->group(function () {
        Route::get('/profesor', 'inicio')->name('profe.inicio');
        Route::get('/profesor/actividades', 'actividades')->name('profe.actividades');
        Route::get('/profesor/actividades/clase', 'actividadesClase')->name('profe.actividadesClase');
        Route::get('/profesor/tutores_alumnos', 'tutores_alumnos')->name('profe.tutoresAlum');
        
        Route::get('/profesor/clases', 'clases')->name('profe.clases');
        Route::get('/profesor/perfil', 'perfil')->name('profe.usuario');
        Route::put('/profesor/perfil/actualizar/contraseña','actualizarContrasenia')->name('profe.actualizarContraseña');
        Route::delete('/profesor/clases/clase/{idClase}/periodo/{idPeriodo}/eliminar/calificaciones-periodo', 'eliminarCalificacionesPeriodo')->name('profe.eliminarCalPer');
        Route::get('/profesor/clases/clase/{idClase}/eliminar/calificaciones-finales', 'eliminarCalificacionFinal')->name('profe.eliminarCalFin');
        Route::delete('/profesor/clases/clase/{idClase}/actividad/{idActividad}/eliminar', 'eliminarActividad')->name('profe.eliminarAct');
        Route::post('/profesor/calificar/actividad', 'almacenarCalificaciones')->name('profe.almacCalificaciones');
        Route::get('/profesor/clases/clase/{idClase}/pasar-lista/{idActividad}', 'paseLista')->name('profe.paseLista');
        Route::get('/profesor/clases/clase/{idClase}/mostrarPaseLista/{idActividad}', 'mostrarPaseLista')->name('profe.mostrarPasLis');
        Route::get('/profesor/clases/clase/{idClase}/calificar/{idActividad}', 'calificarActividad')->name('profe.calificarAct');
        Route::get('/profesor/clases/clase/{idClase}/calificacion-periodo/{idPeriodo}', 'mostrarCalificacionesPer')->name('profe.mostrarCalPer');
        Route::get('/profesor/clases/clase/{idClase}/mostrarCalificacion/{idActividad}', 'mostrarCalificaciones')->name('profe.mostrarCal');
        Route::post('/profesor/calificar/periodo', 'calificarPeriodo')->name('profe.calificarPeriodo');
        Route::post('/profesor/clases/clase/{idClase}/calificacion-final', 'calificarClase')->name('profe.calificarClase');
        Route::get('/profesor/clases/clase/{idClase}/ver/calificacion-final', 'mostrarCalificacionClase')->name('profe.mostrarCalFin');
        
        
        Route::get('/profesor/clases/clase/{idClase}', 'mostrarClase')->name('profe.mostrarClase');
        Route::get('/profesor/personal/obtener', 'obtenerPersonal')->name('obtenerPersonal');
        Route::get('/profesor/clases/obtener/datos/{idPersonal}', 'obtenerDatosClase')->name('obtenerDatosClase');
        Route::get('/profesor/materias/obtener/datos/{idClase}', 'obtenerDatosMateria')->name('obtenerDatosMateria');
        Route::get('/profesor/grados/obtener/datos/{idClase}', 'obtenerDatosGrado')->name('obtenerDatosGrado');
        Route::get('/profesor/grupos/obtener/datos/{idClase}', 'obtenerDatosGrupo')->name('obtenerDatosGrupo');

        Route::post('/profesor/pase-lista', 'agregarPaseLista')->name('profe.agregarPaseL');
        Route::post('/profesor/actividad', 'agregarActividad')->name('profe.agregarActividad');
        Route::put('/profesor/actividad/actualizar', 'actualizarActividad')->name('profe.actActividad');    
        Route::put('/profesor/actividad/pase-lista/actualizar', 'actualizarPaseLista')->name('profe.actPaseLista');        
        Route::put('/profesor/actividades/{idActividad}/edit', 'actualizarActividades')->name('profe.actualizarActividades');
        Route::delete('/profesor/actividades/delete/{actividadesIds}', 'elimPeriodos')->name('profe.elimActividades');

        Route::get('/profesor/buscar/{idClase}/tipos-actividades/{idPeriodo}', 'obtenerTiposActividades')->name('profe.buscarTipAct');
    });
});

Route::controller(SecreController::class)->group(function () {
    Route::get('/secre', 'inicio')->name('secre.inicio');
    Route::get('/secre/alumnosclases', 'alumnosclases')->name('secre.alumnosclases');

    Route::get('/secre/cuentas', 'cuentas')->name('secre.cuentas');
    Route::get('/secre/perfil', 'perfil')->name('secre.perfil');
    Route::put('/secre/perfil/actualizar/contraseña','actualizarContrasenia')->name('secre.actualizarContrasenia');
    Route::delete('/secre/alumnosclases/{idClase}', 'eliminarAlumnosClases')->name('secre.eliminarAlumnosClases');
    Route::delete('/secre/alumnosclases/delete/{clases_alumnosIds}', 'elimAlumnosClases')->name('secre.elimAlumnosClases');
});

Route::controller(TutorController::class)->group(function () {
    Route::get('/tutor', 'inicio')->name('tutor.inicio');
    Route::get('/tutor/perfil', 'perfil')->name('tutor.perfil');
    Route::put('/tutor/perfil/actualizar/contraseña','actualizarContrasenia')->name('tutor.actualizarContrasenia');
    Route::get('/tutorr/calificaciones', 'calificaciones')->name('tutor.calificaciones');
    Route::get('/tutorr/califica', 'cursoHijo')->name('tutor.cursoHijo');
    Route::get('/tutor/hijos/obtener/datos/{idTutor}', 'obtenerDatosHijos')->name('tutor.obtenerDatosHijoss');
    Route::get('/tutor/tutor/obtener', 'obtenerTutor')->name('obtenerTutor');
    Route::get('/tutor/clasesHijos/obtener/datos/{idAlumno}', 'obtenerDatosClase')->name('obtenerDatossClaseA');
    Route::get('/tutor/clasesHijo/clase/{idClase}/tutorado/{idAlumno}', 'mostrarClasesHijo')->name('tutor.mostrarClasessHijo');
    Route::get('/tutor/clasesHi/clase/{idClase}/mostrarCalificacionesHijo/{idActividad}', 'mostrarCalificacionesHijo')->name('tutor.mostrarCalificacionesHijo');
});
