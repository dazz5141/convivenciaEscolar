<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstablecimientoController extends Controller
{
    /**
     * LISTAR ESTABLECIMIENTOS
     */
    public function index()
    {
        if (Auth::user()->rol_id == 1) {

            // Admin General ve todos (activos/inactivos)
            $establecimientos = Establecimiento::orderBy('nombre')->get();

        } else {

            // Otros roles → solo su establecimiento (si está activo)
            $establecimientos = Establecimiento::where('id', Auth::user()->establecimiento_id)
                ->where('activo', 1)
                ->get();
        }

        return view('modulos.establecimientos.index', compact('establecimientos'));
    }

    /**
     * MOSTRAR FORMULARIO CREAR
     */
    public function create()
    {
        $this->authorizeGeneral();

        return view('modulos.establecimientos.create');
    }

    /**
     * GUARDAR NUEVO ESTABLECIMIENTO
     */
    public function store(Request $request)
    {
        $this->authorizeGeneral();

        $data = $request->validate([
            'RBD'          => 'required|string|max:20|unique:establecimientos,RBD',
            'nombre'       => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'dependencia_id' => 'required|exists:dependencias,id',
            'region_id'      => 'required|exists:regiones,id',
            'provincia_id'   => 'required:exists:provincias,id',
            'comuna_id'      => 'required|exists:comunas,id',
        ]);

        $establecimiento = Establecimiento::create($data);

        Auditoria::create([
            'usuario_id'          => Auth::id(),
            'establecimiento_id'  => Auth::user()->establecimiento_id,
            'accion'              => 'create',
            'modulo'              => 'Establecimientos',
            'detalle'             => 'Creó el establecimiento: '.$establecimiento->nombre,
        ]);

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento creado correctamente.');
    }

    /**
     * MOSTRAR DETALLE
     */
    public function show($id)
    {
        $establecimiento = $this->findForUser($id);

        return view('modulos.establecimientos.show', compact('establecimiento'));
    }

    /**
     * FORMULARIO EDICIÓN
     */
    public function edit($id)
    {
        $this->authorizeGeneral();

        $establecimiento = Establecimiento::findOrFail($id);

        return view('modulos.establecimientos.edit', compact('establecimiento'));
    }

    /**
     * ACTUALIZAR ESTABLECIMIENTO
     */
    public function update(Request $request, $id)
    {
        $this->authorizeGeneral();

        $establecimiento = Establecimiento::findOrFail($id);

        $data = $request->validate([
            'RBD'          => 'required|string|max:20|unique:establecimientos,RBD,'.$establecimiento->id,
            'nombre'       => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'dependencia_id' => 'required|exists:dependencias,id',
            'region_id'      => 'required|exists:regiones,id',
            'provincia_id'   => 'required|exists:provincias,id',
            'comuna_id'      => 'required|exists:comunas,id',
        ]);

        $establecimiento->update($data);

        Auditoria::create([
            'usuario_id'          => Auth::id(),
            'establecimiento_id'  => Auth::user()->establecimiento_id,
            'accion'              => 'update',
            'modulo'              => 'Establecimientos',
            'detalle'             => 'Actualizó el establecimiento: '.$establecimiento->nombre,
        ]);

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento actualizado correctamente.');
    }

    /**
     * DESHABILITAR (NO BORRAR)
     */
    public function disable($id)
    {
        $this->authorizeGeneral();

        $establecimiento = Establecimiento::findOrFail($id);

        $establecimiento->update(['activo' => 0]);

        Auditoria::create([
            'usuario_id'          => Auth::id(),
            'establecimiento_id'  => Auth::user()->establecimiento_id,
            'accion'              => 'disable',
            'modulo'              => 'Establecimientos',
            'detalle'             => 'Deshabilitó el establecimiento: '.$establecimiento->nombre,
        ]);

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        $this->authorizeGeneral();

        $establecimiento = Establecimiento::findOrFail($id);

        $establecimiento->update(['activo' => 1]);

        Auditoria::create([
            'usuario_id'          => Auth::id(),
            'establecimiento_id'  => Auth::user()->establecimiento_id,
            'accion'              => 'enable',
            'modulo'              => 'Establecimientos',
            'detalle'             => 'Habilitó el establecimiento: '.$establecimiento->nombre,
        ]);

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento habilitado.');
    }

    /* ============================================================
       MÉTODOS AUXILIARES PRIVADOS
       ============================================================ */

    private function authorizeGeneral()
    {
        if (Auth::user()->rol_id !== 1) {
            abort(403, 'Solo el Administrador General puede realizar esta acción.');
        }
    }

    private function findForUser($id)
    {
        // Admin General → puede ver todos
        if (Auth::user()->rol_id == 1) {
            return Establecimiento::findOrFail($id);
        }

        // Otros roles → solo su establecimiento, pero debe estar activo
        return Establecimiento::where('id', Auth::user()->establecimiento_id)
            ->where('activo', 1)
            ->firstOrFail();
    }
}
