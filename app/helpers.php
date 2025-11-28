<?php

use Illuminate\Support\Facades\Auth;

function canAccess(string $modulo, string $accion = null): bool
{
    $user = Auth::user();

    if (!$user) {
        return false;
    }

    $roles = config('roles');
    $rolId = $user->rol_id;

    if (!isset($roles[$rolId])) {
        return false;
    }

    $accesos = $roles[$rolId]['accesos'];

    // Si no existe el módulo en roles, no puede acceder
    if (!isset($accesos[$modulo])) {
        return false;
    }

    // Si el rol tiene FULL
    if (in_array('full', $accesos[$modulo])) {
        return true;
    }

    // Si no se pidió una acción específica
    if ($accion === null) {
        $permisosValidos = ['view','create','edit','delete','disable'];
        return count(array_intersect($permisosValidos, $accesos[$modulo])) > 0;
    }

    // Verifica la acción
    return in_array($accion, $accesos[$modulo]);
}
