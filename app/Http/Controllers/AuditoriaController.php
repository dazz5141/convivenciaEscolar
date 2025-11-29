<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario;
use App\Models\Establecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    /**
     * LISTADO DE AUDITORÍA
     */
    public function index(Request $request)
    {
        // Permiso general
        if (!canAccess('auditoria', 'view')) {
            abort(403, 'No tienes permiso para ver la auditoría.');
        }

        $usuario = Auth::user();

        // Consulta base
        $query = Auditoria::query()->orderBy('id', 'desc');

        // Multicolegio: solo el Admin General ve todos
        if ($usuario->rol_id !== 1) {
            $query->where('establecimiento_id', $usuario->establecimiento_id);
        } else {
            // Filtrar por establecimiento (admin general)
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->establecimiento_id);
            }
        }

        /* =============================
            FILTROS
        ============================== */

        // Buscar por texto en detalle
        if ($request->filled('buscar')) {
            $query->where('detalle', 'LIKE', '%' . $request->buscar . '%');
        }

        // Por módulo
        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        // Por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Por usuario
        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        // Rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $auditorias = $query->paginate(30)->withQueryString();

        // Para el filtro por usuario
        $usuarios = Usuario::orderBy('nombre')->get();

        // Listado de establecimientos (para el filtro)
        $establecimientos = Establecimiento::orderBy('nombre')->get();

        return view('modulos.auditoria.index', compact('auditorias', 'usuarios', 'establecimientos'));
    }

    /**
     * MOSTRAR DETALLE DE UNA AUDITORÍA
     */
    public function show($id)
    {
        if (!canAccess('auditoria', 'view')) {
            abort(403, 'No tienes permiso para ver este registro.');
        }

        $registro = Auditoria::findOrFail($id);

        // Control multicolegio
        if (Auth::user()->rol_id !== 1 && $registro->establecimiento_id != Auth::user()->establecimiento_id) {
            abort(403, 'No puedes ver acciones de otros establecimientos.');
        }

        return view('modulos.auditoria.show', compact('registro'));
    }
}
