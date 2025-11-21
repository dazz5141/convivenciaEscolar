<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\BitacoraIncidenteController;
use App\Http\Controllers\BitacoraIncidenteObservacionController;
use App\Http\Controllers\BitacoraDocumentoController;
use App\Http\Controllers\SeguimientoEmocionalController;
use App\Http\Controllers\SeguimientoEmocionalObservacionController;
use App\Http\Controllers\MedidaRestaurativaController;
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
use App\Http\Controllers\ConflictoFuncionarioController;
use App\Http\Controllers\ConflictoApoderadoController;
use App\Http\Controllers\DenunciaLeyKarinController;
use App\Http\Controllers\LeyKarinDocumentoController;
use App\Http\Controllers\DerivacionController;
use App\Http\Controllers\Reportes\DashboardReporteController;
use App\Http\Controllers\Reportes\CursoReporteController;
use App\Http\Controllers\Reportes\AlumnoReporteController;
use App\Http\Controllers\Reportes\FuncionarioReporteController;
use App\Http\Controllers\Reportes\EstablecimientoReporteController;

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

    /*--------------------------------------------------------------------------
    | MÓDULO REPORTES
    |--------------------------------------------------------------------------*/
    Route::prefix('modulos/reportes')->name('reportes.')->middleware(['auth', 'establecimiento', 'reportes.role'])->group(function () {

        Route::get('/dashboard', [DashboardReporteController::class, 'index'])->name('dashboard');

        Route::get('/curso', [CursoReporteController::class, 'index'])->name('curso');
        Route::get('/alumno', [AlumnoReporteController::class, 'index'])->name('alumno');
        Route::get('/funcionario', [FuncionarioReporteController::class, 'index'])->name('funcionario');
        Route::get('/establecimiento', [EstablecimientoReporteController::class, 'index'])->name('establecimiento');

        // Reporte PDF por establecimiento
        Route::get('/establecimiento/pdf', [EstablecimientoReporteController::class, 'pdfProfesional'])->name('establecimiento.pdf');
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
    | MÓDULO ROLES
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/roles')->name('roles.')->middleware(['auth', 'establecimiento'])->group(function () {

        Route::get('/', [RolController::class, 'index'])->name('index');
        Route::get('/create', [RolController::class, 'create'])->name('create');
        Route::post('/', [RolController::class, 'store'])->name('store');
        Route::get('/{rol}/edit', [RolController::class, 'edit'])->name('edit');
        Route::put('/{rol}', [RolController::class, 'update'])->name('update');
    });

    /*-------------------------------------------------------------------------
    | MÓDULO USUARIOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/usuarios')->name('usuarios.')->middleware(['auth', 'establecimiento'])->group(function () {

        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/create', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
        Route::put('/{usuario}/disable', [UsuarioController::class, 'disable'])->name('disable');
        Route::put('/{usuario}/enable', [UsuarioController::class, 'enable'])->name('enable');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO CONVIVENCIA ESCOLAR
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/convivencia-escolar')->name('convivencia.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | BITÁCORA DE INCIDENTES
        |--------------------------------------------------------------------------
        */
        Route::prefix('bitacora')->name('bitacora.')->group(function () {
            Route::get('/', [BitacoraIncidenteController::class, 'index'])->name('index');
            Route::get('/crear', [BitacoraIncidenteController::class, 'create'])->name('create');
            Route::post('/', [BitacoraIncidenteController::class, 'store'])->name('store');
            Route::get('/{id}', [BitacoraIncidenteController::class, 'show'])->name('show');
            Route::get('/{id}/editar', [BitacoraIncidenteController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BitacoraIncidenteController::class, 'update'])->name('update');
            Route::post('/{id}/observaciones', [BitacoraIncidenteObservacionController::class, 'store'])->name('observaciones.store');

            /*
            |--------------------------------------------------------------------------
            | DOCUMENTOS – BITÁCORA DE INCIDENTES
            |--------------------------------------------------------------------------
            */
            Route::prefix('{incidente}/documentos')->name('documentos.')->group(function () {
                Route::get('/', [BitacoraDocumentoController::class, 'index'])->name('index');
                Route::post('/', [BitacoraDocumentoController::class, 'store'])->name('store');
                Route::put('/{id}/deshabilitar', [BitacoraDocumentoController::class, 'disable'])->name('disable');
                Route::put('/{id}/habilitar', [BitacoraDocumentoController::class, 'enable'])->name('enable');
            });
        });


        /*
        |--------------------------------------------------------------------------
        | SEGUIMIENTO EMOCIONAL
        |--------------------------------------------------------------------------
        */
        Route::prefix('seguimiento')->name('seguimiento.')->group(function () {
            Route::get('/',           [SeguimientoEmocionalController::class, 'index'])->name('index');
            Route::get('/crear',      [SeguimientoEmocionalController::class, 'create'])->name('create');
            Route::post('/',          [SeguimientoEmocionalController::class, 'store'])->name('store');
            Route::get('/{id}',       [SeguimientoEmocionalController::class, 'show'])->name('show');
            Route::get('/{id}/editar',[SeguimientoEmocionalController::class, 'edit'])->name('edit');
            Route::put('/{id}',       [SeguimientoEmocionalController::class, 'update'])->name('update');
            Route::post('/{id}/observaciones', [SeguimientoEmocionalObservacionController::class, 'store'])->name('observaciones.store');
        });


        /*
        |--------------------------------------------------------------------------
        | MEDIDAS RESTAURATIVAS
        |--------------------------------------------------------------------------
        */
        Route::prefix('medidas')->name('medidas.')->group(function () {

            Route::get('/',          [MedidaRestaurativaController::class, 'index'])->name('index');
            Route::get('/crear',     [MedidaRestaurativaController::class, 'create'])->name('create');
            Route::post('/',         [MedidaRestaurativaController::class, 'store'])->name('store');
            Route::get('/{id}',      [MedidaRestaurativaController::class, 'show'])->name('show');
            Route::get('/{id}/editar',[MedidaRestaurativaController::class, 'edit'])->name('edit');
            Route::put('/{id}',      [MedidaRestaurativaController::class, 'update'])->name('update');
        });

        /*
        |--------------------------------------------------------------------------
        | DERIVACIONES – CONVIVENCIA ESCOLAR
        |--------------------------------------------------------------------------
        */
        Route::prefix('derivaciones')->name('derivaciones.')->group(function () {

            Route::get('/', [DerivacionController::class, 'index'])->name('index');
            Route::get('/crear', [DerivacionController::class, 'create'])->name('create');
            Route::post('/', [DerivacionController::class, 'store'])->name('store');
            Route::get('/{derivacion}', [DerivacionController::class, 'show'])->name('show');
            Route::get('/{derivacion}/editar', [DerivacionController::class, 'edit'])->name('edit');
            Route::put('/{derivacion}', [DerivacionController::class, 'update'])->name('update');
        });

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
        Route::get('/retiros/{retiro}/edit', [RetiroAnticipadoController::class, 'edit'])->name('retiros.edit');
        Route::put('/retiros/{retiro}', [RetiroAnticipadoController::class, 'update'])->name('retiros.update');
        Route::get('/retiros/{retiro}', [RetiroAnticipadoController::class, 'show'])->name('retiros.show');


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
    | MÓDULO LEY KARIN – Conflictos y Denuncias
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos/ley-karin')->name('leykarin.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CONFLICTO ENTRE FUNCIONARIOS
        |--------------------------------------------------------------------------
        */
        Route::get('/conflictos-funcionarios', [ConflictoFuncionarioController::class, 'index'])->name('conflictos-funcionarios.index');
        Route::get('/conflictos-funcionarios/create', [ConflictoFuncionarioController::class, 'create'])->name('conflictos-funcionarios.create');
        Route::post('/conflictos-funcionarios', [ConflictoFuncionarioController::class, 'store'])->name('conflictos-funcionarios.store');
        Route::get('/conflictos-funcionarios/{conflictoFuncionario}', [ConflictoFuncionarioController::class, 'show'])->name('conflictos-funcionarios.show');
        Route::get('/conflictos-funcionarios/{conflictoFuncionario}/edit', [ConflictoFuncionarioController::class, 'edit'])->name('conflictos-funcionarios.edit');
        Route::put('/conflictos-funcionarios/{conflictoFuncionario}', [ConflictoFuncionarioController::class, 'update'])->name('conflictos-funcionarios.update');

        /*
        |--------------------------------------------------------------------------
        | CONFLICTO ENTRE APODERADOS Y FUNCIONARIOS
        |--------------------------------------------------------------------------
        */
        Route::get('/conflictos-apoderados', [ConflictoApoderadoController::class, 'index'])->name('conflictos-apoderados.index');
        Route::get('/conflictos-apoderados/create', [ConflictoApoderadoController::class, 'create'])->name('conflictos-apoderados.create');
        Route::post('/conflictos-apoderados', [ConflictoApoderadoController::class, 'store'])->name('conflictos-apoderados.store');
        Route::get('/conflictos-apoderados/{conflictoApoderado}', [ConflictoApoderadoController::class, 'show'])->name('conflictos-apoderados.show');
        Route::get('/conflictos-apoderados/{conflictoApoderado}/edit', [ConflictoApoderadoController::class, 'edit'])->name('conflictos-apoderados.edit');
        Route::put('/conflictos-apoderados/{conflictoApoderado}', [ConflictoApoderadoController::class, 'update'])->name('conflictos-apoderados.update');
    
        /*
        |--------------------------------------------------------------------------
        | DENUNCIAS LEY KARIN
        |--------------------------------------------------------------------------
        */
        Route::get('/denuncias', [DenunciaLeyKarinController::class, 'index'])->name('denuncias.index');
        Route::get('/denuncias/create', [DenunciaLeyKarinController::class, 'create'])->name('denuncias.create');
        Route::post('/denuncias', [DenunciaLeyKarinController::class, 'store'])->name('denuncias.store');
        Route::get('/denuncias/{denuncia}', [DenunciaLeyKarinController::class, 'show'])->name('denuncias.show');
        Route::get('/denuncias/{denuncia}/edit', [DenunciaLeyKarinController::class, 'edit'])->name('denuncias.edit');
        Route::put('/denuncias/{denuncia}', [DenunciaLeyKarinController::class, 'update'])->name('denuncias.update');

        /*--------------------------------------------------------------------------
        | DOCUMENTOS DE DENUNCIAS LEY KARIN
        |--------------------------------------------------------------------------
        */
        Route::get('/{denuncia}/documentos', [LeyKarinDocumentoController::class, 'index'])->name('documentos.index');
        Route::post('/{denuncia}/documentos', [LeyKarinDocumentoController::class, 'store'])->name('documentos.store');
        Route::put('/documentos/{id}/deshabilitar', [LeyKarinDocumentoController::class, 'disable'])->name('documentos.disable');
        Route::put('/documentos/{id}/habilitar', [LeyKarinDocumentoController::class, 'enable'])->name('documentos.enable');
    });

    /*
    |--------------------------------------------------------------------------
    | DUMMY ROUTES 
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

    // ADMIN
    crudDummy('auditoria', 'auditoria');

});
