<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::view('/', 'index')->name('landing');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (require login + establecimiento)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'establecimiento'])->group(function () {

    # Dashboards por rol
    Route::view('/dashboard/admin', 'dashboard.roles.admin')->name('dashboard.admin');
    Route::view('/dashboard/establecimiento', 'dashboard.roles.establecimiento')->name('dashboard.establecimiento');
    Route::view('/dashboard/inspector-general', 'dashboard.roles.inspector-general')->name('dashboard.inspector.general');
    Route::view('/dashboard/inspector', 'dashboard.roles.inspector')->name('dashboard.inspector');
    Route::view('/dashboard/docente', 'dashboard.roles.docente')->name('dashboard.docente');
    Route::view('/dashboard/psicologo', 'dashboard.roles.psicologo')->name('dashboard.psicologo');
    Route::view('/dashboard/asistente', 'dashboard.roles.asistente')->name('dashboard.asistente');
    Route::view('/dashboard/convivencia', 'dashboard.roles.convivencia')->name('dashboard.convivencia');

    # Dashboard general
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');

    # Módulos
    function crudViews($base, $folder) {
        Route::view("/modulos/$base", "modulos.$folder.index")->name("$base.index");
        Route::view("/modulos/$base/crear", "modulos.$folder.create")->name("$base.create");
        Route::view("/modulos/$base/editar", "modulos.$folder.edit")->name("$base.edit");
        Route::view("/modulos/$base/ver", "modulos.$folder.show")->name("$base.show");
    }

    /* MÓDULOS PRINCIPALES */
    crudViews('bitacora', 'bitacora');
    crudViews('alumnos', 'alumnos');
    crudViews('citaciones', 'citaciones');
    crudViews('seguimiento-emocional', 'seguimiento-emocional');
    crudViews('conflicto-apoderado', 'conflictos-apoderados');
    crudViews('conflicto-funcionario', 'conflictos-funcionarios');
    crudViews('denuncias-ley-karin', 'denuncias-ley-karin');
    crudViews('accidentes', 'accidentes');
    crudViews('retiros', 'retiros');
    crudViews('atrasos-asistencia', 'atrasos-asistencia');
    crudViews('libro-novedades', 'libro-novedades');
    crudViews('derivaciones', 'derivaciones');
    crudViews('medidas-restaurativas', 'medidas-restaurativas');
    crudViews('pie', 'pie');

    /* MÓDULOS ADMINISTRATIVOS */
    crudViews('funcionarios', 'funcionarios');
    crudViews('cursos', 'cursos');
    crudViews('establecimientos', 'establecimientos');
    crudViews('usuarios', 'usuarios');
    crudViews('roles', 'roles');
    crudViews('auditoria', 'auditoria');
    crudViews('documentos', 'documentos');
});
