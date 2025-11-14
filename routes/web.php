<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\BitacoraIncidenteController;
use App\Http\Controllers\BitacoraIncidenteObservacionController;
use App\Http\Controllers\SeguimientoEmocionalController;
use App\Http\Controllers\SeguimientoEmocionalObservacionController;
use App\Http\Controllers\ProfesionalPieController;
use App\Http\Controllers\EstudiantePieController;
use App\Http\Controllers\IntervencionPieController;
use App\Http\Controllers\InformePieController;
use App\Http\Controllers\PlanIndividualPieController;
use App\Http\Controllers\DerivacionPieController;
use App\Http\Controllers\HistorialPieController;
use App\Http\Controllers\AccidenteEscolarController;
use App\Http\Controllers\CitacionApoderadoController;
use App\Http\Controllers\NovedadInspectoriaController;
use App\Http\Controllers\AsistenciaEventoController;
use App\Http\Controllers\RetiroAnticipadoController;

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
| SELECCIÓN DE ESTABLECIMIENTO
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
    | DASHBOARD PRINCIPAL Y POR ROLES
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        Route::view('/admin', 'dashboard.roles.admin')->name('dashboard.admin');
        Route::view('/establecimiento', 'dashboard.roles.establecimiento')->name('dashboard.establecimiento');
        Route::view('/inspector-general', 'dashboard.roles.inspector-general')->name('dashboard.inspector.general');
        Route::view('/inspector', 'dashboard.roles.inspector')->name('dashboard.inspector');
        Route::view('/docente', 'dashboard.roles.docente')->name('dashboard.docente');
        Route::view('/psicologo', 'dashboard.roles.psicologo')->name('dashboard.psicologo');
        Route::view('/asistente', 'dashboard.roles.asistente')->name('dashboard.asistente');
        Route::view('/convivencia', 'dashboard.roles.convivencia')->name('dashboard.convivencia');
    });


    /*
    |--------------------------------------------------------------------------
    | MÓDULO ESTABLECIMIENTOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/establecimientos')->group(function () {

        Route::get('/', [EstablecimientoController::class, 'index'])->name('establecimientos.index');
        Route::get('/create', [EstablecimientoController::class, 'create'])->name('establecimientos.create');
        Route::post('/', [EstablecimientoController::class, 'store'])->name('establecimientos.store');

        Route::get('/{id}', [EstablecimientoController::class, 'show'])->name('establecimientos.show');
        Route::get('/{id}/edit', [EstablecimientoController::class, 'edit'])->name('establecimientos.edit');
        Route::put('/{id}', [EstablecimientoController::class, 'update'])->name('establecimientos.update');

        Route::put('/{id}/deshabilitar', [EstablecimientoController::class, 'disable'])->name('establecimientos.disable');
        Route::put('/{id}/habilitar', [EstablecimientoController::class, 'enable'])->name('establecimientos.enable');
    });


    /*
    |--------------------------------------------------------------------------
    | MÓDULO FUNCIONARIOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/funcionarios')->group(function () {

        Route::get('/', [FuncionarioController::class, 'index'])->name('funcionarios.index');
        Route::get('/create', [FuncionarioController::class, 'create'])->name('funcionarios.create');
        Route::post('/', [FuncionarioController::class, 'store'])->name('funcionarios.store');

        Route::get('/{id}', [FuncionarioController::class, 'show'])->name('funcionarios.show');
        Route::get('/{id}/edit', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
        Route::put('/{id}', [FuncionarioController::class, 'update'])->name('funcionarios.update');

        Route::put('/{id}/deshabilitar', [FuncionarioController::class, 'disable'])->name('funcionarios.disable');
        Route::put('/{id}/habilitar', [FuncionarioController::class, 'enable'])->name('funcionarios.enable');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO ALUMNOS
    |--------------------------------------------------------------------------
    */

    Route::prefix('modulos/alumnos')->group(function () {

        Route::get('/', [AlumnoController::class, 'index'])->name('alumnos.index');
        Route::get('/create', [AlumnoController::class, 'create'])->name('alumnos.create');
        Route::post('/', [AlumnoController::class, 'store'])->name('alumnos.store');

        Route::get('/{id}', [AlumnoController::class, 'show'])->name('alumnos.show');
        Route::get('/{id}/edit', [AlumnoController::class, 'edit'])->name('alumnos.edit');
        Route::put('/{id}', [AlumnoController::class, 'update'])->name('alumnos.update');

        Route::put('/{id}/deshabilitar', [AlumnoController::class, 'disable'])->name('alumnos.disable');
        Route::put('/{id}/habilitar', [AlumnoController::class, 'enable'])->name('alumnos.enable');

        Route::get('alumnos/{id}/cambiar-curso', [AlumnoController::class, 'cambiarCursoForm'])->name('alumnos.cambiarCurso.form');
        Route::post('alumnos/{id}/cambiar-curso', [AlumnoController::class, 'cambiarCurso'])->name('alumnos.cambiarCurso');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO CURSOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/cursos')->group(function () {

        Route::get('/', [CursoController::class, 'index'])->name('cursos.index');
        Route::get('/create', [CursoController::class, 'create'])->name('cursos.create');
        Route::post('/', [CursoController::class, 'store'])->name('cursos.store');

        Route::get('/{id}', [CursoController::class, 'show'])->name('cursos.show');
        Route::get('/{id}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
        Route::put('/{id}', [CursoController::class, 'update'])->name('cursos.update');

        Route::put('/{id}/deshabilitar', [CursoController::class, 'disable'])->name('cursos.disable');
        Route::put('/{id}/habilitar', [CursoController::class, 'enable'])->name('cursos.enable');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO APODERADOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/apoderados')->group(function () {

        Route::get('/', [ApoderadoController::class, 'index'])->name('apoderados.index');
        Route::get('/create', [ApoderadoController::class, 'create'])->name('apoderados.create');
        Route::post('/', [ApoderadoController::class, 'store'])->name('apoderados.store');

        Route::get('/{id}', [ApoderadoController::class, 'show'])->name('apoderados.show');
        Route::get('/{id}/edit', [ApoderadoController::class, 'edit'])->name('apoderados.edit');
        Route::put('/{id}', [ApoderadoController::class, 'update'])->name('apoderados.update');

        Route::put('/{id}/deshabilitar', [ApoderadoController::class, 'disable'])->name('apoderados.disable');
        Route::put('/{id}/habilitar', [ApoderadoController::class, 'enable'])->name('apoderados.enable');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO BITÁCORA DE INCIDENTES
    |--------------------------------------------------------------------------
    */

    Route::prefix('modulos/bitacora')->group(function () {
        Route::get('/', [BitacoraIncidenteController::class, 'index'])->name('bitacora.index');
        Route::get('/create', [BitacoraIncidenteController::class, 'create'])->name('bitacora.create');
        Route::post('/', [BitacoraIncidenteController::class, 'store'])->name('bitacora.store');
        Route::get('/{id}', [BitacoraIncidenteController::class, 'show'])->name('bitacora.show');
        Route::get('/{id}/edit', [BitacoraIncidenteController::class, 'edit'])->name('bitacora.edit');
        Route::put('/{id}', [BitacoraIncidenteController::class, 'update'])->name('bitacora.update');
        Route::post('bitacora/{id}/observaciones',[BitacoraIncidenteObservacionController::class, 'store'])->name('bitacora.observaciones.store');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO SEGUIMIENTO EMOCIONAL
    |--------------------------------------------------------------------------
    */

    Route::prefix('modulos/seguimiento-emocional')->middleware(['auth', 'establecimiento'])->group(function () {
        Route::get('/',           [SeguimientoEmocionalController::class, 'index'])->name('seguimiento.index');
        Route::get('/crear',      [SeguimientoEmocionalController::class, 'create'])->name('seguimiento.create');
        Route::post('/',          [SeguimientoEmocionalController::class, 'store'])->name('seguimiento.store');
        Route::get('/{id}',       [SeguimientoEmocionalController::class, 'show'])->name('seguimiento.show');
        Route::get('/{id}/edit',  [SeguimientoEmocionalController::class, 'edit'])->name('seguimiento.edit');
        Route::put('/{id}',       [SeguimientoEmocionalController::class, 'update'])->name('seguimiento.update');
        // Observaciones al seguimiento emocional
        Route::post('/{id}/observaciones', [SeguimientoEmocionalObservacionController::class, 'store'])->name('seguimiento.observaciones.store');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO PIE – Programa de Integración Escolar
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/pie')->name('pie.')->group(function () {

        /*
        |----------------------------------------------------------------------
        | PROFESIONALES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/profesionales', [ProfesionalPIEController::class, 'index'])->name('profesionales.index');
        Route::get('/profesionales/create', [ProfesionalPIEController::class, 'create'])->name('profesionales.create');
        Route::post('/profesionales', [ProfesionalPIEController::class, 'store'])->name('profesionales.store');
        Route::get('/profesionales/{id}', [ProfesionalPIEController::class, 'show'])->name('profesionales.show');
        Route::delete('/profesionales/{id}', [ProfesionalPIEController::class, 'destroy'])->name('profesionales.destroy');


        /*
        |----------------------------------------------------------------------
        | ESTUDIANTES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/estudiantes', [EstudiantePIEController::class, 'index'])->name('estudiantes.index');
        Route::get('/estudiantes/create', [EstudiantePIEController::class, 'create'])->name('estudiantes.create');
        Route::post('/estudiantes', [EstudiantePIEController::class, 'store'])->name('estudiantes.store');
        Route::get('/estudiantes/{estudiantePIE}', [EstudiantePIEController::class, 'show'])->name('estudiantes.show');
        Route::post('/pie/estudiantes/{estudiantePIE}/egresar', [EstudiantePIEController::class, 'egresar'])->name('estudiantes.egresar');

        /*
        |----------------------------------------------------------------------
        | INTERVENCIONES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/intervenciones', [IntervencionPIEController::class, 'index'])->name('intervenciones.index');
        Route::get('/intervenciones/create', [IntervencionPIEController::class, 'create'])->name('intervenciones.create');
        Route::post('/intervenciones', [IntervencionPIEController::class, 'store'])->name('intervenciones.store');
        Route::get('/intervenciones/{intervencionPIE}', [IntervencionPIEController::class, 'show'])->name('intervenciones.show');


        /*
        |----------------------------------------------------------------------
        | INFORMES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/informes', [InformePIEController::class, 'index'])->name('informes.index');
        Route::get('/informes/create', [InformePIEController::class, 'create'])->name('informes.create');
        Route::post('/informes', [InformePIEController::class, 'store'])->name('informes.store');
        Route::get('/informes/{id}', [InformePIEController::class, 'show'])->name('informes.show');


        /*
        |----------------------------------------------------------------------
        | PLANES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/planes', [PlanIndividualPIEController::class, 'index'])->name('planes.index');
        Route::get('/planes/create', [PlanIndividualPIEController::class, 'create'])->name('planes.create');
        Route::post('/planes', [PlanIndividualPIEController::class, 'store'])->name('planes.store');
        Route::get('/planes/{id}', [PlanIndividualPIEController::class, 'show'])->name('planes.show');


        /*
        |----------------------------------------------------------------------
        | DERIVACIONES PIE
        |----------------------------------------------------------------------
        */
        Route::get('/derivaciones', [DerivacionPIEController::class, 'index'])->name('derivaciones.index');
        Route::get('/derivaciones/create', [DerivacionPIEController::class, 'create'])->name('derivaciones.create');
        Route::post('/derivaciones', [DerivacionPIEController::class, 'store'])->name('derivaciones.store');
        Route::get('/derivaciones/{derivacionPIE}', [DerivacionPIEController::class, 'show'])->name('derivaciones.show');


        /*
        |----------------------------------------------------------------------
        | HISTORIAL PIE
        |----------------------------------------------------------------------
        */
        Route::get('/historial/{estudiantePIE}', [HistorialPieController::class, 'index'])->name('historial.index');
        Route::get('/historial/detalle/{tipo}/{id}', [HistorialPieController::class, 'show'])->name('historial.show');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO INSPECTORÍA – Gestión Interna del Colegio
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/inspectoria')->name('inspectoria.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | NOVEDADES DE INSPECTORÍA
        |--------------------------------------------------------------------------
        */
        Route::get('/novedades', [NovedadInspectoriaController::class, 'index'])->name('novedades.index');
        Route::get('/novedades/create', [NovedadInspectoriaController::class, 'create'])->name('novedades.create');
        Route::post('/novedades', [NovedadInspectoriaController::class, 'store'])->name('novedades.store');
        Route::get('/novedades/{novedad}/edit', [NovedadInspectoriaController::class, 'edit'])->name('novedades.edit');
        Route::put('/novedades/{novedad}', [NovedadInspectoriaController::class, 'update'])->name('novedades.update');
        Route::get('/novedades/{novedad}', [NovedadInspectoriaController::class, 'show'])->name('novedades.show');

        /*
        |--------------------------------------------------------------------------
        | ASISTENCIA / ATRASOS
        |--------------------------------------------------------------------------
        */
        Route::get('/asistencia', [AsistenciaEventoController::class, 'index'])->name('asistencia.index');
        Route::get('/asistencia/create', [AsistenciaEventoController::class, 'create'])->name('asistencia.create');
        Route::post('/asistencia', [AsistenciaEventoController::class, 'store'])->name('asistencia.store');
        Route::get('/asistencia/{evento}', [AsistenciaEventoController::class, 'show'])->name('asistencia.show');
        Route::get('/asistencia/{evento}/edit', [AsistenciaEventoController::class, 'edit'])->name('asistencia.edit');
        Route::put('/asistencia/{evento}', [AsistenciaEventoController::class, 'update'])->name('asistencia.update');


        /*
        |--------------------------------------------------------------------------
        | RETIROS ANTICIPADOS
        |--------------------------------------------------------------------------
        */
        Route::get('/retiros', [RetiroAnticipadoController::class, 'index'])->name('retiros.index');
        Route::get('/retiros/create', [RetiroAnticipadoController::class, 'create'])->name('retiros.create');
        Route::post('/retiros', [RetiroAnticipadoController::class, 'store'])->name('retiros.store');
        Route::get('/retiros/{retiro}', [RetiroAnticipadoController::class, 'show'])->name('retiros.show');
        Route::get('/retiros/{retiro}/edit', [RetiroAnticipadoController::class, 'edit'])->name('retiros.edit');
        Route::put('/retiros/{retiro}', [RetiroAnticipadoController::class, 'update'])->name('retiros.update');


        /*
        |--------------------------------------------------------------------------
        | ACCIDENTES ESCOLARES
        |--------------------------------------------------------------------------
        */
        Route::get('/accidentes', [AccidenteEscolarController::class, 'index'])->name('accidentes.index');
        Route::get('/accidentes/create', [AccidenteEscolarController::class, 'create'])->name('accidentes.create');
        Route::post('/accidentes', [AccidenteEscolarController::class, 'store'])->name('accidentes.store');
        Route::get('/accidentes/{accidente}', [AccidenteEscolarController::class, 'show'])->name('accidentes.show');
        Route::get('/accidentes/{accidente}/edit', [AccidenteEscolarController::class, 'edit'])->name('accidentes.edit');
        Route::put('/accidentes/{accidente}', [AccidenteEscolarController::class, 'update'])->name('accidentes.update');


        /*
        |--------------------------------------------------------------------------
        | CITACIONES A APODERADOS
        |--------------------------------------------------------------------------
        */
        Route::get('/citaciones', [CitacionApoderadoController::class, 'index'])->name('citaciones.index');
        Route::get('/citaciones/create', [CitacionApoderadoController::class, 'create'])->name('citaciones.create');
        Route::post('/citaciones', [CitacionApoderadoController::class, 'store'])->name('citaciones.store');
        Route::get('/citaciones/{citacion}', [CitacionApoderadoController::class, 'show'])->name('citaciones.show');
        Route::get('/citaciones/{citacion}/edit', [CitacionApoderadoController::class, 'edit'])->name('citaciones.edit');
        Route::put('/citaciones/{citacion}', [CitacionApoderadoController::class, 'update'])->name('citaciones.update');
    });

    /*
    |--------------------------------------------------------------------------
    | DUMMY ROUTES (SOLO LOS QUE AÚN NO EXISTEN)
    |--------------------------------------------------------------------------
    */

    if (!function_exists('crudDummy')) {
        function crudDummy($base, $folder) {
            Route::view("/modulos/$base", "modulos.$folder.index")->name("$base.index");
            Route::view("/modulos/$base/crear", "modulos.$folder.create")->name("$base.create");
            Route::view("/modulos/$base/editar", "modulos.$folder.edit")->name("$base.edit");
            Route::view("/modulos/$base/ver", "modulos.$folder.show")->name("$base.show");
        }
    }

    // PRINCIPALES
    crudDummy('conflicto-apoderado', 'conflicto-apoderado');
    crudDummy('conflicto-funcionario', 'conflicto-funcionario');
    crudDummy('denuncia-ley-karin', 'denuncia-ley-karin');

    crudDummy('derivaciones', 'derivaciones');
    crudDummy('medidas-restaurativas', 'medidas-restaurativas');

    // ADMIN
    crudDummy('roles', 'roles');
    crudDummy('auditoria', 'auditoria');
    crudDummy('documentos', 'documentos');

});
