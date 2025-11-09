<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Este archivo es requerido por Laravel.
| Puedes dejarlo vacío si no usarás APIs públicas.
|
*/

Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});
