<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // LISTADO PRINCIPAL
    public function index()
    {
        if (!canAccess('roles', 'view')) {
            abort(403, 'No tienes permisos para ver roles.');
        }

        $roles = Rol::orderBy('id')->get();
        return view('modulos.roles.index', compact('roles'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        if (!canAccess('roles', 'create')) {
            abort(403, 'No tienes permisos para crear roles.');
        }

        return view('modulos.roles.create');
    }

    // GUARDAR NUEVO ROL
    public function store(Request $request)
    {
        if (!canAccess('roles', 'create')) {
            abort(403, 'No tienes permisos para crear roles.');
        }

        $request->validate([
            'nombre' => 'required|string|max:80|unique:roles,nombre',
        ]);

        Rol::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    // FORMULARIO EDITAR
    public function edit(Rol $rol)
    {
        if (!canAccess('roles', 'edit')) {
            abort(403, 'No tienes permisos para editar roles.');
        }

        return view('modulos.roles.edit', compact('rol'));
    }

    // ACTUALIZAR ROL
    public function update(Request $request, Rol $rol)
    {
        if (!canAccess('roles', 'edit')) {
            abort(403, 'No tienes permisos para actualizar roles.');
        }

        $request->validate([
            'nombre' => 'required|string|max:80|unique:roles,nombre,' . $rol->id,
        ]);

        $rol->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }
}
