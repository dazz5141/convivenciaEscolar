<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EstablecimientoMiddleware
{
    public function handle($request, Closure $next)
    {
        // Usuario no autenticado = no puede continuar
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();

        // 1. Admin General (rol_id = 1) tiene acceso libre a todos los colegios
        if ($usuario->rol_id === 1) {

            // Si no hay establecimiento seleccionado en sesión, asignar uno
            if (!session('establecimiento_id')) {
                $primerEst = \App\Models\Establecimiento::where('activo', 1)->first();
                if ($primerEst) {
                    session(['establecimiento_id' => $primerEst->id]);
                }
            }
            return $next($request);
        }

        // 2. Si no tiene establecimiento asignado => acceso prohibido
        if (!$usuario->establecimiento_id) {
            abort(403, 'Este usuario no tiene un establecimiento asignado.');
        }

        // 3. Guardar establecimiento en sesión
        session(['establecimiento_id' => $usuario->establecimiento_id]);

        return $next($request);
    }
}
