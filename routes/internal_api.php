<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\ComunaController;

Route::get('/provincias/{region}', [ProvinciaController::class, 'porRegion']);
Route::get('/comunas/{provincia}', [ComunaController::class, 'porProvincia']);
