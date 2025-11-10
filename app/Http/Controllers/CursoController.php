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
        $establecimientoId = session('establecimiento_id');

        return view('modulos.cursos.create', compact('establecimientoId'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $establecimientoId = session('establecimiento_id');

        $request->validate([
            'anio' => 'required|integer|min:2000|max:2100',
            'nivel' => 'required|string|max:50',
            'letra' => 'required|string|max:5',
        ]);

        Curso::create([
            'anio' => $request->anio,
            'nivel' => $request->nivel,
            'letra' => $request->letra,
            'establecimiento_id' => $establecimientoId,
            'activo' => 1,
        ]);

        return redirect()->route('cursos.index')
            ->with('success', 'Curso creado correctamente.');
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        $curso = Curso::with('alumnos')->findOrFail($id);

        return view('modulos.cursos.show', compact('curso'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);

        return view('modulos.cursos.edit', compact('curso'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
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

        return redirect()->route('cursos.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * DESHABILITAR
     */
    public function disable($id)
    {
        Curso::findOrFail($id)->update(['activo' => 0]);

        return redirect()->route('cursos.index')
            ->with('success', 'Curso deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        Curso::findOrFail($id)->update(['activo' => 1]);

        return redirect()->route('cursos.index')
            ->with('success', 'Curso habilitado.');
    }
}
