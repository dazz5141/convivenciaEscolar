<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register routes for the application.
     */
    public function boot(): void
    {
        $this->routes(function () {

            // API normal de Laravel
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rutas web normales
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // NUESTRA API INTERNA (para regiones, provincias, comunas, alumnos, apoderados, etc.)
            Route::middleware(['web', 'auth', 'establecimiento'])
                ->prefix('api-interna')
                ->group(base_path('routes/internal_api.php'));
        });
    }
}
