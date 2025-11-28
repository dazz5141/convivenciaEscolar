<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\Region;
use App\Models\Establecimiento;
use Illuminate\Http\Request;

class ApoderadoController extends Controller
{
    /**
     * Listado de apoderados con filtros
     */
    public function index(Request $request)
    {
        // PERMISO: listar apoderados
        if (!canAccess('apoderados', 'view')) {
            abort(403);
        }

        $establecimientoId = session('establecimiento_id');

        $query = Apoderado::where('establecimiento_id', $establecimientoId);

        if ($request->buscar) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('run', 'LIKE', "%$buscar%")
                  ->orWhereRaw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%$buscar%"]);
            });
        }

        if ($request->estado !== null && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        $apoderados = $query->orderBy('apellido_paterno')->paginate(20);

        return view('modulos.apoderados.index', compact('apoderados'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        // PERMISO: crear apoderado
        if (!canAccess('apoderados', 'create')) {
            abort(403);
        }

        $regiones = Region::orderBy('nombre')->get();

        return view('modulos.apoderados.create', compact('regiones'));
    }

    /**
     * Guardar apoderado
     */
    public function store(Request $request)
    {
        // PERMISO: guardar apoderado
        if (!canAccess('apoderados', 'create')) {
            abort(403);
        }

        // -------------------------------------------
        // Asegurar establecimiento_id no nulo
        // -------------------------------------------
        $establecimientoId = session('establecimiento_id');

        if (!$establecimientoId) {
            $primerEst = Establecimiento::where('activo', 1)->first();

            if ($primerEst) {
                $establecimientoId = $primerEst->id;
                session(['establecimiento_id' => $establecimientoId]);
            } else {
                return back()->withErrors([
                    'error' => 'No existe un establecimiento activo para asignar este apoderado.'
                ]);
            }
        }

        // -------------------------------------------
        // Validación
        // -------------------------------------------
        $request->validate([
            'run' => 'required|string|max:20|unique:apoderados,run',
            'nombre' => 'required|string|max:120',
            'apellido_paterno' => 'required|string|max:120',
            'apellido_materno' => 'required|string|max:120',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regiones,id',
            'provincia_id' => 'nullable|exists:provincias,id',
            'comuna_id' => 'nullable|exists:comunas,id',
        ]);

        // -------------------------------------------
        // Crear apoderado
        // -------------------------------------------
        Apoderado::create([
            'run' => $request->run,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'region_id' => $request->region_id,
            'provincia_id' => $request->provincia_id,
            'comuna_id' => $request->comuna_id,
            'establecimiento_id' => $establecimientoId,
            'activo' => 1,
        ]);

        return redirect()->route('apoderados.index')
            ->with('success', 'Apoderado registrado correctamente.');
    }

    /**
     * Ver ficha del apoderado
     */
    public function show($id)
    {
        // PERMISO: ver apoderado
        if (!canAccess('apoderados', 'view')) {
            abort(403);
        }

        $apoderado = Apoderado::with(['alumnos.curso', 'region', 'provincia', 'comuna'])
            ->findOrFail($id);

        return view('modulos.apoderados.show', compact('apoderado'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        // PERMISO: editar apoderado
        if (!canAccess('apoderados', 'edit')) {
            abort(403);
        }

        $apoderado = Apoderado::findOrFail($id);

        $regiones = Region::orderBy('nombre')->get();

        $provinciaActual = $apoderado->provincia_id;
        $comunaActual    = $apoderado->comuna_id;

        return view('modulos.apoderados.edit', compact(
            'apoderado', 'regiones',
            'provinciaActual', 'comunaActual'
        ));
    }

    /**
     * Actualizar apoderado
     */
    public function update(Request $request, $id)
    {
        // PERMISO: actualizar apoderado
        if (!canAccess('apoderados', 'edit')) {
            abort(403);
        }

        $apoderado = Apoderado::findOrFail($id);

        $request->validate([
            'run' => 'required|string|max:20|unique:apoderados,run,' . $apoderado->id,
            'nombre' => 'required|string|max:120',
            'apellido_paterno' => 'required|string|max:120',
            'apellido_materno' => 'required|string|max:120',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regiones,id',
            'provincia_id' => 'nullable|exists:provincias,id',
            'comuna_id' => 'nullable|exists:comunas,id',
        ]);

        $apoderado->update([
            'run' => $request->run,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'region_id' => $request->region_id,
            'provincia_id' => $request->provincia_id,
            'comuna_id' => $request->comuna_id,
        ]);

        return redirect()->route('apoderados.index')
            ->with('success', 'Apoderado actualizado correctamente.');
    }

    /**
     * Deshabilitar
     */
    public function disable($id)
    {
        // PERMISO: cambiar estado
        if (!canAccess('apoderados', 'edit')) {
            abort(403);
        }

        $apoderado = Apoderado::findOrFail($id);
        $apoderado->update(['activo' => 0]);

        return redirect()->route('apoderados.index')
            ->with('success', 'Apoderado deshabilitado correctamente.');
    }

    /**
     * Habilitar
     */
    public function enable($id)
    {
        // PERMISO: cambiar estado
        if (!canAccess('apoderados', 'edit')) {
            abort(403);
        }

        $apoderado = Apoderado::findOrFail($id);
        $apoderado->update(['activo' => 1]);

        return redirect()->route('apoderados.index')
            ->with('success', 'Apoderado habilitado correctamente.');
    }
}
