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
        return view('modulos.funcionarios.create', [
            'cargos'         => Cargo::all(),
            'tiposContrato'  => TipoContrato::all(),
            'regiones'       => Region::all(),
        ]);
    }


    public function store(StoreFuncionarioRequest $request)
    {
        $establecimientoId = session('establecimiento_id');

        // Crear funcionario
        $funcionario = Funcionario::create([
            ...$request->validated(),
            'establecimiento_id' => $establecimientoId,
            'activo' => 1,
        ]);

        // Buscar cargo
        $cargo = $funcionario->cargo->nombre;

        // Asignar rol automático desde config
        $rolId = config("convivencia.roles_por_cargo.$cargo");

        // Generar password automático
        $password = strtolower(Str::slug($cargo)) . '123';

        // Generar email automático
        $dominio = config('convivencia.dominio_email');
        $emailBase = strtolower(Str::slug($funcionario->nombre . '.' . $funcionario->apellido_paterno));
        $email = $emailBase . '@' . $dominio;

        // Crear usuario
        Usuario::create([
            'email'             => $email,
            'password'          => Hash::make($password),
            'rol_id'            => $rolId,
            'funcionario_id'    => $funcionario->id,
            'establecimiento_id'=> $establecimientoId,
            'nombre'            => $funcionario->nombre,
            'apellido_paterno'  => $funcionario->apellido_paterno,
            'apellido_materno'  => $funcionario->apellido_materno,
            'activo'            => 1,
        ]);

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario y usuario creados correctamente.');
    }


    public function edit($id)
    {
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
        $funcionario->update($request->validated());

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario actualizado correctamente.');
    }

    public function show($id)
    {
        $funcionario = Funcionario::findOrFail($id);

        return view('modulos.funcionarios.show', compact('funcionario'));
    }


    public function disable($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update(['activo' => 0]);

        // También desactivar usuario asociado si se quiere
        if ($funcionario->usuario) {
            $funcionario->usuario->update(['activo' => 0]);
        }

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario deshabilitado correctamente.');
    }

    public function enable($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update(['activo' => 1]);

        if ($funcionario->usuario) {
            $funcionario->usuario->update(['activo' => 1]);
        }

        return redirect()->route('funcionarios.index')->with('success', 'Funcionario habilitado correctamente.');
    }
}
