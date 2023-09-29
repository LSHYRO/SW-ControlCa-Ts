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
// Pagina de inicio
Route::get('/', [AdminController::class, 'index'])->name('admin.inicio');
Route::get('/profesores', [AdminController::class, 'profesores'])->name('admin.profesores');
Route::get('/alumnos', [AdminController::class, 'alumnos'])->name('admin.alumnos');
Route::get('/directivos', [AdminController::class, 'directivos'])->name('admin.directivos');
Route::get('/tutores', [AdminController::class, 'tutores'])->name('admin.tutores');
Route::get('/materias', [AdminController::class, 'materias'])->name('admin.materias');

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