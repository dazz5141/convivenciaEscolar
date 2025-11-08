<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTAS DEL FRONTEND (solo vistas)
|--------------------------------------------------------------------------
|
| Estas rutas permiten visualizar TODO el frontend generado por Bolt
| sin requerir backend ni controladores todavía.
|
*/

# Landing Page
Route::view('/', 'index')->name('landing');

# Login
Route::view('/login', 'auth.login')->name('login');

# Dashboard
Route::view('/dashboard', 'dashboard.index')->name('dashboard');

/*
|--------------------------------------------------------------------------
| MÓDULOS DEL SISTEMA
|--------------------------------------------------------------------------
|
| Cada módulo tiene sus propias vistas: index, create, edit, show
| Estas rutas son temporales SOLO para visualizar el frontend.
| Luego serán reemplazadas por controladores reales.
|
*/

# Helper para evitar escribir 300 rutas
function crudViews($base, $folder) {
    Route::view("/modulos/$base", "modulos.$folder.index")->name("$base.index");
    Route::view("/modulos/$base/crear", "modulos.$folder.create")->name("$base.create");
    Route::view("/modulos/$base/editar", "modulos.$folder.edit")->name("$base.edit");
    Route::view("/modulos/$base/ver", "modulos.$folder.show")->name("$base.show");
}

/* ✅ MÓDULOS PRINCIPALES */
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

/* ✅ MÓDULOS ADMINISTRATIVOS */
crudViews('funcionarios', 'funcionarios');
crudViews('cursos', 'cursos');
crudViews('establecimientos', 'establecimientos');
crudViews('usuarios', 'usuarios');
crudViews('roles', 'roles');
crudViews('auditoria', 'auditoria');
crudViews('documentos', 'documentos');

/*
|--------------------------------------------------------------------------
| FIN DE RUTAS
|--------------------------------------------------------------------------
*/
