<?php

use App\Http\Controllers\AdminController;
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
    Route::get('/alumnos', 'alumnos')->name('admin.alumnos');
    Route::get('/directivos', 'directivos')->name('admin.directivos');
    Route::get('/tutores', 'tutores')->name('admin.tutores');
    Route::get('/materias', 'materias')->name('admin.materias');

    Route::post('/profesores', 'addProfesores')->name('admin.addProfesores');
    Route::post('/materias', 'addMaterias')->name('admin.addMaterias');
    Route::post('/tutores', 'addTutores')->name('admin.addTutores');

    Route::get('/admin/search', 'buscarT')->name('ad.busquedaTutor');    
    Route::delete('/profesores/{idPersonal}', 'eliminarProfesores')->name('admin.eliminarProfesores');

    Route::put('/profesores/{idPersonal}/edit', 'actualizarProfesor')->name('admin.actualizarProfesores');
    

});

Route::resource('Admin', AdminController::class);
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
