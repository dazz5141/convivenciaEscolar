<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Funcionario;
use App\Models\Establecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // LISTADO PRINCIPAL
    public function index(Request $request)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'view')) {
            abort(403, 'No tienes permisos para ver usuarios.');
        }

        $query = Usuario::with(['rol', 'establecimiento', 'funcionario'])
            ->orderBy('id', 'asc');

        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        if ($request->filled('establecimiento_id')) {
            $query->where('establecimiento_id', $request->establecimiento_id);
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('email', 'like', "%$buscar%")
                  ->orWhere('nombre', 'like', "%$buscar%")
                  ->orWhere('apellido_paterno', 'like', "%$buscar%")
                  ->orWhere('apellido_materno', 'like', "%$buscar%");
            });
        }

        $usuarios = $query->paginate(15);

        return view('modulos.usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => Rol::orderBy('id')->get(),
            'establecimientos' => Establecimiento::orderBy('nombre')->get()
        ]);
    }

    // FORMULARIO CREAR
    public function create()
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'create')) {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        return view('modulos.usuarios.create', [
            'roles' => Rol::orderBy('id')->get(),
            'establecimientos' => Establecimiento::orderBy('nombre')->get()
        ]);
    }

    // GUARDAR USUARIO
    public function store(Request $request)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'create')) {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        $request->validate([
            'email' => 'nullable|email|max:255|unique:usuarios,email',
            'password' => 'required|min:6|max:255',
            'rol_id' => 'required|exists:roles,id',

            // puede venir o no
            'funcionario_id' => 'nullable|exists:funcionarios,id',
            'establecimiento_id' => 'nullable|exists:establecimientos,id',

            // solo para usuarios SIN funcionario
            'nombre' => 'nullable|string|max:120',
            'apellido_paterno' => 'nullable|string|max:120',
            'apellido_materno' => 'nullable|string|max:120'
        ]);

        // ============================================================
        // SI SE SELECCIONÓ FUNCIONARIO → NO DUPLICAR USUARIO
        // ============================================================
        if ($request->filled('funcionario_id')) {

            $func = Funcionario::findOrFail($request->funcionario_id);

            if ($func->usuario) {
                return back()->with('error', 'Este funcionario ya tiene un usuario.');
            }

            // Datos automáticos desde el funcionario
            $data = [
                'email' => $request->email, // opcional
                'password' => Hash::make($request->password),
                'rol_id' => $request->rol_id,
                'funcionario_id' => $func->id,
                'establecimiento_id' => $func->establecimiento_id,
                'nombre' => $func->nombre,
                'apellido_paterno' => $func->apellido_paterno,
                'apellido_materno' => $func->apellido_materno,
                'activo' => 1
            ];

        } else {
            // ============================================================
            // USUARIO SIN FUNCIONARIO → DATOS MANUALES
            // ============================================================
            $data = [
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => $request->rol_id,
                'funcionario_id' => null,
                'establecimiento_id' => $request->establecimiento_id,
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'activo' => 1
            ];
        }

        Usuario::create($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // FORMULARIO EDITAR
    public function edit(Usuario $usuario)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'update')) {
            abort(403, 'No tienes permisos para editar usuarios.');
        }

        return view('modulos.usuarios.edit', [
            'usuario' => $usuario,
            'roles' => Rol::orderBy('id')->get(),
            'establecimientos' => Establecimiento::orderBy('nombre')->get()
        ]);
    }

    // ACTUALIZAR
    public function update(Request $request, Usuario $usuario)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'update')) {
            abort(403, 'No tienes permisos para actualizar usuarios.');
        }

        $request->validate([
            'email' => "nullable|email|max:255|unique:usuarios,email,{$usuario->id}",
            'password' => 'nullable|min:6|max:255',
            'rol_id' => 'required|exists:roles,id',
            'establecimiento_id' => 'nullable|exists:establecimientos,id',
            'nombre' => 'nullable|string|max:120',
            'apellido_paterno' => 'nullable|string|max:120',
            'apellido_materno' => 'nullable|string|max:120'
        ]);

        $usuario->update([
            'email' => $request->email,
            'rol_id' => $request->rol_id,
            'establecimiento_id' => $request->establecimiento_id,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function disable(Usuario $usuario)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'delete')) {
            abort(403, 'No tienes permisos para deshabilitar usuarios.');
        }

        $usuario->update(['activo' => 0]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario deshabilitado correctamente.');
    }

    public function enable(Usuario $usuario)
    {
        // 🔒 PERMISO
        if (!canAccess('usuarios', 'delete')) {
            abort(403, 'No tienes permisos para habilitar usuarios.');
        }

        $usuario->update(['activo' => 1]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario habilitado correctamente.');
    }
}
