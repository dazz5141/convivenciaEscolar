<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Cargo;
use App\Models\TipoContrato;
use App\Models\Usuario;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Comuna;
use App\Http\Requests\StoreFuncionarioRequest;
use App\Http\Requests\UpdateFuncionarioRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FuncionarioController extends Controller
{
    public function index()
    {
        // PERMISO
        if (!canAccess('funcionarios_index', 'view')) {
            abort(403, 'No tienes permiso para ver funcionarios.');
        }

        $establecimientoId = session('establecimiento_id');

        $funcionarios = Funcionario::where('establecimiento_id', $establecimientoId)
            ->when(request('estado'), fn($q) => $q->where('activo', request('estado')))
            ->when(request('buscar'), function ($q) {
                $buscar = request('buscar');
                $q->where(function ($s) use ($buscar) {
                    $s->where('nombre', 'LIKE', "%$buscar%")
                        ->orWhere('apellido_paterno', 'LIKE', "%$buscar%")
                        ->orWhere('apellido_materno', 'LIKE', "%$buscar%")
                        ->orWhere('run', 'LIKE', "%$buscar%");
                });
            })
            ->orderBy('apellido_paterno')
            ->paginate(15);

        return view('modulos.funcionarios.index', compact('funcionarios'));
    }


    public function create()
    {
        // PERMISO
        if (!canAccess('funcionarios_create', 'view')) {
            abort(403, 'No tienes permiso para crear funcionarios.');
        }

        return view('modulos.funcionarios.create', [
            'cargos'         => Cargo::all(),
            'tiposContrato'  => TipoContrato::all(),
            'regiones'       => Region::all(),
        ]);
    }


    public function store(StoreFuncionarioRequest $request)
    {
        // PERMISO
        if (!canAccess('funcionarios_create', 'action')) {
            abort(403, 'No tienes permiso para crear funcionarios.');
        }

        $establecimientoId = session('establecimiento_id');

        $funcionario = Funcionario::create([
            ...$request->validated(),
            'establecimiento_id' => $establecimientoId,
            'activo' => 1,
        ]);

        // AUDITORÍA
        logAuditoria(
            accion: 'create',
            modulo: 'Funcionarios',
            detalle: 'Registró al funcionario: ' . $funcionario->nombre . ' ' . $funcionario->apellido_paterno,
            establecimiento_id: $funcionario->establecimiento_id
        );

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionario creado correctamente.');
    }


    public function edit($id)
    {
        // PERMISO
        if (!canAccess('funcionarios_edit', 'view')) {
            abort(403, 'No tienes permiso para editar funcionarios.');
        }

        $funcionario = Funcionario::findOrFail($id);

        $cargos = Cargo::all();
        $tiposContrato = TipoContrato::all();

        // Necesario para los select dinámicos
        $regiones = Region::orderBy('nombre')->get();

        return view('modulos.funcionarios.edit', [
            'funcionario'    => $funcionario,
            'cargos'         => $cargos,
            'tiposContrato'  => $tiposContrato,
            'regiones'       => $regiones,
        ]);
    }


    public function update(UpdateFuncionarioRequest $request, Funcionario $funcionario)
    {
        // PERMISO
        if (!canAccess('funcionarios_edit', 'action')) {
            abort(403, 'No tienes permiso para actualizar funcionarios.');
        }

        $funcionario->update($request->validated());

        // AUDITORÍA
        logAuditoria(
            accion: 'update',
            modulo: 'Funcionarios',
            detalle: 'Actualizó al funcionario: ' . $funcionario->nombre . ' ' . $funcionario->apellido_paterno,
            establecimiento_id: $funcionario->establecimiento_id
        );

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario actualizado correctamente.');
    }


    public function show($id)
    {
        // PERMISO
        if (!canAccess('funcionarios_show', 'view')) {
            abort(403, 'No tienes permiso para ver funcionarios.');
        }

        $funcionario = Funcionario::findOrFail($id);

        return view('modulos.funcionarios.show', compact('funcionario'));
    }


    public function disable($id)
    {
        // PERMISO
        if (!canAccess('funcionarios_edit', 'action')) {
            abort(403, 'No tienes permiso para deshabilitar funcionarios.');
        }

        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update(['activo' => 0]);

        // También desactivar usuario asociado si se quiere
        if ($funcionario->usuario) {
            $funcionario->usuario->update(['activo' => 0]);
        }

        // AUDITORÍA
        logAuditoria(
            accion: 'disable',
            modulo: 'Funcionarios',
            detalle: 'Deshabilitó al funcionario: ' . $funcionario->nombre . ' ' . $funcionario->apellido_paterno,
            establecimiento_id: $funcionario->establecimiento_id
        );

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario deshabilitado correctamente.');
    }


    public function enable($id)
    {
        // PERMISO
        if (!canAccess('funcionarios_edit', 'action')) {
            abort(403, 'No tienes permiso para habilitar funcionarios.');
        }

        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update(['activo' => 1]);

        if ($funcionario->usuario) {
            $funcionario->usuario->update(['activo' => 1]);
        }

        // AUDITORÍA
        logAuditoria(
            accion: 'enable',
            modulo: 'Funcionarios',
            detalle: 'Habilitó al funcionario: ' . $funcionario->nombre . ' ' . $funcionario->apellido_paterno,
            establecimiento_id: $funcionario->establecimiento_id
        );

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario habilitado correctamente.');
    }
}
