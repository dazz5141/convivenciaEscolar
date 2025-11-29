<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Establecimiento;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * LISTADO + FILTROS
     */
    public function index(Request $request)
    {
        // ============================================
        // PERMISO: VER LISTADO DE CURSOS
        // ============================================
        if (!canAccess('cursos', 'view')) {
            abort(403, 'No tienes permiso para ver los cursos.');
        }

        $establecimientoId = session('establecimiento_id');

        $query = Curso::where('establecimiento_id', $establecimientoId);

        // Filtro año
        if ($request->anio) {
            $query->where('anio', $request->anio);
        }

        // Filtro nivel
        if ($request->nivel) {
            $query->where('nivel', $request->nivel);
        }

        // Filtro estado
        if ($request->estado !== null && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        $cursos = $query->orderBy('nivel')->orderBy('letra')->paginate(20);

        // Años disponibles
        $anios = Curso::where('establecimiento_id', $establecimientoId)
            ->select('anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->get();

        // Niveles disponibles
        $niveles = Curso::where('establecimiento_id', $establecimientoId)
            ->select('nivel')
            ->distinct()
            ->orderBy('nivel')
            ->get();

        return view('modulos.cursos.index', compact('cursos', 'anios', 'niveles'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        // ============================================
        // PERMISO: CREAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'create')) {
            abort(403, 'No tienes permiso para crear cursos.');
        }

        $establecimientoId = session('establecimiento_id');

        return view('modulos.cursos.create', compact('establecimientoId'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        // ============================================
        // PERMISO: GUARDAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'create')) {
            abort(403, 'No tienes permiso para crear cursos.');
        }

        $establecimientoId = session('establecimiento_id');

        $request->validate([
            'anio' => 'required|integer|min:2000|max:2100',
            'nivel' => 'required|string|max:50',
            'letra' => 'required|string|max:5',
        ]);

        $curso = Curso::create([
            'anio' => $request->anio,
            'nivel' => $request->nivel,
            'letra' => $request->letra,
            'establecimiento_id' => $establecimientoId,
            'activo' => 1,
        ]);

        // Auditoría
        logAuditoria(
            'create',
            'Cursos',
            'Creó el curso: ' . $curso->nivel . ' ' . $curso->letra . ' (' . $curso->anio . ')',
            $curso->establecimiento_id
        );

        return redirect()->route('cursos.index')
            ->with('success', 'Curso creado correctamente.');
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        // ============================================
        // PERMISO: VER DETALLE DE CURSO
        // ============================================
        if (!canAccess('cursos', 'view')) {
            abort(403, 'No tienes permiso para ver cursos.');
        }

        $curso = Curso::with('alumnos')->findOrFail($id);

        return view('modulos.cursos.show', compact('curso'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        // ============================================
        // PERMISO: EDITAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'edit')) {
            abort(403, 'No tienes permiso para editar cursos.');
        }

        $curso = Curso::findOrFail($id);

        return view('modulos.cursos.edit', compact('curso'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        // ============================================
        // PERMISO: ACTUALIZAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'edit')) {
            abort(403, 'No tienes permiso para editar cursos.');
        }

        $curso = Curso::findOrFail($id);

        $request->validate([
            'anio' => 'required|integer|min:2000|max:2100',
            'nivel' => 'required|string|max:50',
            'letra' => 'required|string|max:5',
        ]);

        $curso->update([
            'anio' => $request->anio,
            'nivel' => $request->nivel,
            'letra' => $request->letra,
        ]);

        // Auditoría
        logAuditoria(
            'update',
            'Cursos',
            'Actualizó el curso: ' . $curso->nivel . ' ' . $curso->letra . ' (' . $curso->anio . ')',
            $curso->establecimiento_id
        );

        return redirect()->route('cursos.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * DESHABILITAR
     */
    public function disable($id)
    {
        // ============================================
        // PERMISO: DESHABILITAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'edit')) {
            abort(403, 'No tienes permiso para deshabilitar cursos.');
        }

        $curso = Curso::findOrFail($id);
        $curso->update(['activo' => 0]);

        // Auditoría
        logAuditoria(
            'disable',
            'Cursos',
            'Deshabilitó el curso: ' . $curso->nivel . ' ' . $curso->letra . ' (' . $curso->anio . ')',
            $curso->establecimiento_id
        );

        return redirect()->route('cursos.index')
            ->with('success', 'Curso deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        // ============================================
        // PERMISO: HABILITAR CURSOS
        // ============================================
        if (!canAccess('cursos', 'edit')) {
            abort(403, 'No tienes permiso para habilitar cursos.');
        }

        $curso = Curso::findOrFail($id);
        $curso->update(['activo' => 1]);

        // Auditoría
        logAuditoria(
            'enable',
            'Cursos',
            'Habilitó el curso: ' . $curso->nivel . ' ' . $curso->letra . ' (' . $curso->anio . ')',
            $curso->establecimiento_id
        );

        return redirect()->route('cursos.index')
            ->with('success', 'Curso habilitado.');
    }
}
