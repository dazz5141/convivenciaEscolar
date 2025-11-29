<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Dependencia;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Comuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstablecimientoController extends Controller
{
    /**
     * LISTAR ESTABLECIMIENTOS
     */
    public function index()
    {
        if (!canAccess('establecimientos', 'view')) {
            abort(403, 'No tienes permiso para ver establecimientos.');
        }

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
        if (!canAccess('establecimientos', 'create')) {
            abort(403, 'No tienes permiso para crear establecimientos.');
        }

        return view('modulos.establecimientos.create', [
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'regiones'     => Region::orderBy('nombre')->get(),
            'provincias'   => Provincia::orderBy('nombre')->get(),
            'comunas'      => Comuna::orderBy('nombre')->get(),
        ]);
    }

    /**
     * GUARDAR NUEVO ESTABLECIMIENTO
     */
    public function store(Request $request)
    {
        if (!canAccess('establecimientos', 'create')) {
            abort(403, 'No tienes permiso para crear establecimientos.');
        }

        $data = $request->validate([
            'RBD'          => 'required|string|max:20|unique:establecimientos,RBD',
            'nombre'       => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'dependencia_id' => 'required|exists:dependencias,id',
            'region_id'      => 'required|exists:regiones,id',
            'provincia_id'   => 'required|exists:provincias,id',
            'comuna_id'      => 'required|exists:comunas,id',
        ]);

        $establecimiento = Establecimiento::create($data);

        // AUDITORÍA
        logAuditoria(
            accion: 'create',
            modulo: 'Establecimientos',
            detalle: 'Creó el establecimiento: ' . $establecimiento->nombre,
            establecimiento_id: $establecimiento->id
        );

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento creado correctamente.');
    }

    /**
     * MOSTRAR DETALLE
     */
    public function show($id)
    {
        if (!canAccess('establecimientos', 'view')) {
            abort(403, 'No tienes permiso para ver establecimientos.');
        }

        $establecimiento = $this->findForUser($id);

        return view('modulos.establecimientos.show', compact('establecimiento'));
    }

    /**
     * FORMULARIO EDICIÓN
     */
    public function edit($id)
    {
        if (!canAccess('establecimientos', 'edit')) {
            abort(403, 'No tienes permiso para editar establecimientos.');
        }

        $establecimiento = Establecimiento::findOrFail($id);

        return view('modulos.establecimientos.edit', [
            'establecimiento' => $establecimiento,
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'regiones'     => Region::orderBy('nombre')->get(),
            'provincias'   => Provincia::orderBy('nombre')->get(),
            'comunas'      => Comuna::orderBy('nombre')->get(),
        ]);
    }

    /**
     * ACTUALIZAR ESTABLECIMIENTO
     */
    public function update(Request $request, $id)
    {
        if (!canAccess('establecimientos', 'edit')) {
            abort(403, 'No tienes permiso para editar establecimientos.');
        }

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

        // AUDITORÍA
        logAuditoria(
            accion: 'update',
            modulo: 'Establecimientos',
            detalle: 'Actualizó el establecimiento: ' . $establecimiento->nombre,
            establecimiento_id: $establecimiento->id
        );

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento actualizado correctamente.');
    }

    /**
     * DESHABILITAR (NO BORRAR)
     */
    public function disable($id)
    {
        if (!canAccess('establecimientos', 'edit')) {
            abort(403, 'No tienes permiso para deshabilitar establecimientos.');
        }

        $establecimiento = Establecimiento::findOrFail($id);

        $establecimiento->update(['activo' => 0]);

        // AUDITORÍA
        logAuditoria(
            accion: 'disable',
            modulo: 'Establecimientos',
            detalle: 'Deshabilitó el establecimiento: ' . $establecimiento->nombre,
            establecimiento_id: $establecimiento->id
        );

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        if (!canAccess('establecimientos', 'edit')) {
            abort(403, 'No tienes permiso para habilitar establecimientos.');
        }

        $establecimiento = Establecimiento::findOrFail($id);

        $establecimiento->update(['activo' => 1]);

        // AUDITORÍA
        logAuditoria(
            accion: 'enable',
            modulo: 'Establecimientos',
            detalle: 'Habilitó el establecimiento: ' . $establecimiento->nombre,
            establecimiento_id: $establecimiento->id
        );

        return redirect()->route('establecimientos.index')->with('success', 'Establecimiento habilitado.');
    }

    /* ============================================================
       MÉTODOS AUXILIARES PRIVADOS
       ============================================================ */

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
