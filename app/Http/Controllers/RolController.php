<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // LISTADO PRINCIPAL
    public function index()
    {
        $roles = Rol::orderBy('id')->get();
        return view('modulos.roles.index', compact('roles'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        return view('modulos.roles.create');
    }

    // GUARDAR NUEVO ROL
    public function store(Request $request)
    {
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
        return view('modulos.roles.edit', compact('rol'));
    }

    // ACTUALIZAR ROL
    public function update(Request $request, Rol $rol)
    {
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
