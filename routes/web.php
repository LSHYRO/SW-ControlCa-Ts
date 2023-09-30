<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ejemploRController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Pagina Admin
Route::controller(AdminController::class)->group(function(){
Route::get('/', 'index')->name('admin.inicio');
Route::get('/profesores', 'profesores')->name('admin.profesores');
Route::get('/alumnos', 'alumnos')->name('admin.alumnos');
Route::get('/directivos', 'directivos')->name('admin.directivos');
Route::get('/tutores', 'tutores')->name('admin.tutores');
Route::get('/materias', 'materias')->name('admin.materias');

Route::post('/profesores', 'addProfesores')->name('admin.addProfesores');
Route::post('/materias', 'addMaterias')->name('admin.addMaterias');
Route::post('/tutores', 'addTutores')->name('admin.addTutores');
});
//Tipos de rutas con controladores
/*
Route::get('cursos', [ejemploRController::class, 'index']);

Route::get('create',[ejemploRController::class, 'create']);

Route::get('cursos/{curso}',[ejemploRController::class, 'show']);
*/

//Grupo de rutas
Route::controller(ejemploRController::class)->group(function(){
    Route::get('cursos', 'index')->name('cursos.principal');
    Route::get('create', 'create');
    Route::get('cursos/{curso}', 'show');
});

/*
Route::get('/', function () {
    return view('welcome');
});
*/