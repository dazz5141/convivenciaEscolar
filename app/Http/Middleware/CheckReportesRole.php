<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckReportesRole
{
    /**
     * Roles permitidos para acceder al módulo Reportes
     */
    private $rolesPermitidos = [1, 2, 3, 4, 8];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si no hay usuario o su rol no está permitido
        if (!$user || !in_array($user->rol_id, $this->rolesPermitidos)) {
            abort(403, 'No tienes permiso para acceder al módulo de reportes.');
        }

        return $next($request);
    }
}
