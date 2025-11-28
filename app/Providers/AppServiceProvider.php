<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DIRECTIVAS BASE PARA ACCESOS
        |--------------------------------------------------------------------------
        */
        \Blade::directive('canAccess', function ($exp) {
            return "<?php if(canAccess($exp)): ?>";
        });

        \Blade::directive('endCanAccess', function () {
            return "<?php endif; ?>";
        });


        /*
        |--------------------------------------------------------------------------
        | DIRECTIVAS SIMPLIFICADAS (ver, crear, editar, etc.)
        |--------------------------------------------------------------------------
        */

        // Ver módulo (listar)
        \Blade::if('ver', function ($modulo) {
            return canAccess($modulo, 'view');
        });

        // Crear registro
        \Blade::if('crear', function ($modulo) {
            return canAccess($modulo, 'create');
        });

        // Editar registro
        \Blade::if('editar', function ($modulo) {
            return canAccess($modulo, 'edit');
        });

        // Desactivar registro
        \Blade::if('desactivar', function ($modulo) {
            return canAccess($modulo, 'disable');
        });

        // Eliminar registro
        \Blade::if('eliminar', function ($modulo) {
            return canAccess($modulo, 'delete');
        });

        // Acceso total
        \Blade::if('full', function ($modulo) {
            return canAccess($modulo, 'full');
        });


        /*
        |--------------------------------------------------------------------------
        | DIRECTIVA PARA MENÚ SIMPLE (si tiene acceso a un módulo)
        |--------------------------------------------------------------------------
        */
        \Blade::if('menu', function ($modulo) {
            return canAccess($modulo);
        });


        /*
        |--------------------------------------------------------------------------
        | DIRECTIVA PARA MENÚ MÚLTIPLE (si tiene acceso a CUALQUIERA de los módulos)
        |--------------------------------------------------------------------------
        */
        \Blade::if('menuAny', function ($modulos) {

            // Si pasan un string en lugar de array, lo convertimos
            if (!is_array($modulos)) {
                $modulos = [$modulos];
            }

            foreach ($modulos as $m) {
                if (canAccess($m)) {
                    return true;
                }
            }
            return false;
        });
    }
}
