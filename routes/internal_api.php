<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\ComunaController;
use App\Models\Alumno;
use App\Models\Funcionario;

Route::get('/provincias/{region}', [ProvinciaController::class, 'porRegion']);
Route::get('/comunas/{provincia}', [ComunaController::class, 'porProvincia']);

/*
|--------------------------------------------------------------------------
| API Interna - Búsquedas de Personas
|--------------------------------------------------------------------------
*/

// Buscar alumnos por RUN, nombre o apellido
Route::get('/buscar/alumnos', function (Request $request) {
    $q = $request->query('q');

    if (!$q) {
        return response()->json([]);
    }

    $alumnos = Alumno::where('run', 'like', "%{$q}%")
        ->orWhere('nombre', 'like', "%{$q}%")
        ->orWhere('apellido_paterno', 'like', "%{$q}%")
        ->orWhere('apellido_materno', 'like', "%{$q}%")
        ->with('curso')
        ->take(15)
        ->get()
        ->map(function ($a) {
            return [
                'id' => $a->id,
                'run' => $a->run,
                'nombre_completo' =>
                    "{$a->apellido_paterno} {$a->apellido_materno}, {$a->nombre}",
                'curso' => $a->curso->nombre ?? 'Sin curso'
            ];
        });

    return response()->json($alumnos);
});


// Buscar funcionarios por RUN, nombre o apellido
Route::get('/buscar/funcionarios', function (Request $request) {
    $q = $request->query('q');

    if (!$q) {
        return response()->json([]);
    }

    $funcionarios = Funcionario::where('run', 'like', "%{$q}%")
        ->orWhere('nombre', 'like', "%{$q}%")
        ->orWhere('apellido_paterno', 'like', "%{$q}%")
        ->orWhere('apellido_materno', 'like', "%{$q}%")
        ->with('cargo')
        ->take(15)
        ->get()
        ->map(function ($f) {
            return [
                'id' => $f->id,
                'run' => $f->run,
                'nombre_completo' =>
                    "{$f->apellido_paterno} {$f->apellido_materno}, {$f->nombre}",
                'cargo' => $f->cargo->nombre ?? '',
            ];
        });

    return response()->json($funcionarios);
});
