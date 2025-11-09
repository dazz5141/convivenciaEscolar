<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuncionarioController;

/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA (Landing)
|--------------------------------------------------------------------------
*/
Route::view('/', 'index')->name('landing');


/*
|--------------------------------------------------------------------------
| SELECCIÓN DE ESTABLECIMIENTO (solo autenticado)
|--------------------------------------------------------------------------
*/
Route::post('/seleccionar-establecimiento', function () {

    request()->validate([
        'establecimiento_id' => 'required|exists:establecimientos,id',
    ]);

    session(['establecimiento_id' => request('establecimiento_id')]);

    return back();

})->middleware('auth')->name('establecimiento.select');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (LOGIN + ESTABLECIMIENTO)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'establecimiento'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PRINCIPAL (ahora dinámico)
    |--------------------------------------------------------------------------
    |
    | El DashboardController decide a qué vista dirigir según el rol.
    |
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARDS POR ROL (vistas directas si quieres navegar manualmente)
    |--------------------------------------------------------------------------
    */
    Route::view('/dashboard/admin', 'dashboard.roles.admin')->name('dashboard.admin');
    Route::view('/dashboard/establecimiento', 'dashboard.roles.establecimiento')->name('dashboard.establecimiento');
    Route::view('/dashboard/inspector-general', 'dashboard.roles.inspector-general')->name('dashboard.inspector.general');
    Route::view('/dashboard/inspector', 'dashboard.roles.inspector')->name('dashboard.inspector');
    Route::view('/dashboard/docente', 'dashboard.roles.docente')->name('dashboard.docente');
    Route::view('/dashboard/psicologo', 'dashboard.roles.psicologo')->name('dashboard.psicologo');
    Route::view('/dashboard/asistente', 'dashboard.roles.asistente')->name('dashboard.asistente');
    Route::view('/dashboard/convivencia', 'dashboard.roles.convivencia')->name('dashboard.convivencia');


    /*
    |--------------------------------------------------------------------------
    | CRUD REAL DE ESTABLECIMIENTOS (habilitar/deshabilitar)
    |--------------------------------------------------------------------------
    */
    Route::resource('establecimientos', EstablecimientoController::class)
        ->except(['destroy']);

    Route::put('/establecimientos/{id}/deshabilitar',
        [EstablecimientoController::class, 'disable']
    )->name('establecimientos.disable');

    Route::put('/establecimientos/{id}/habilitar',
        [EstablecimientoController::class, 'enable']
    )->name('establecimientos.enable');

    /*
    |--------------------------------------------------------------------------
    | CRUD REAL DE FUNCIONARIOS (habilitar/deshabilitar)
    |--------------------------------------------------------------------------
    */

    Route::resource('funcionarios', FuncionarioController::class)
        ->except(['destroy']);

    Route::put('/funcionarios/{id}/deshabilitar',
        [FuncionarioController::class, 'disable']
    )->name('funcionarios.disable');

    Route::put('/funcionarios/{id}/habilitar',
        [FuncionarioController::class, 'enable']
    )->name('funcionarios.enable'); 
});
