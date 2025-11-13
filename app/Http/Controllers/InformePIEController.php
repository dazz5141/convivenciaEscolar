<?php

namespace App\Http\Controllers;

use App\Models\InformePIE;
use App\Models\EstudiantePIE;
use Illuminate\Http\Request;

class InformePIEController extends Controller
{
    /**
     * Listado de informes PIE del establecimiento actual
     */
    public function index(Request $request)
    {
        $establecimiento_id = session('establecimiento_id');

        // Estudiantes PIE del establecimiento
        $estudiantes = EstudiantePIE::select('estudiantes_pie.*')
            ->join('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->where('estudiantes_pie.establecimiento_id', $establecimiento_id)
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->with('alumno')
            ->get();

        // Tipos de informe distintos (para el filtro)
        $tipos = InformePIE::select('tipo')
            ->distinct()
            ->whereNotNull('tipo')
            ->orderBy('tipo')
            ->pluck('tipo');

        // Filtrado
        $query = InformePIE::with(['estudiante.alumno'])
            ->where('establecimiento_id', $establecimiento_id);

        if ($request->filled('estudiante_pie_id')) {
            $query->where('estudiante_pie_id', $request->estudiante_pie_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $informes = $query->orderBy('fecha', 'desc')->paginate(20);

        return view('modulos.pie.informes.index', compact('estudiantes', 'informes', 'tipos'));
    }

    /**
     * Crear informe desde la ficha del estudiante PIE
     */
    public function create($estudiante_pie_id = null)
    {
        // 🔹 Obtener tipos de informe distintos ya usados
        $tipos = InformePIE::select('tipo')
            ->distinct()
            ->whereNotNull('tipo')
            ->orderBy('tipo')
            ->pluck('tipo');

        return view('modulos.pie.informes.create', compact('estudiante_pie_id', 'tipos'));
    }

    /**
     * Guardar informe PIE
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_pie_id' => 'required|exists:estudiantes_pie,id',
            'fecha'             => 'required|date',
            'tipo'              => 'required|string|max:120',
            'contenido'         => 'nullable|string',
        ]);

        // Validar establecimiento del estudiante PIE
        $this->validarEstablecimiento(
            EstudiantePIE::findOrFail($request->estudiante_pie_id)
        );

        InformePIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'estudiante_pie_id'  => $request->estudiante_pie_id,
            'fecha'              => $request->fecha,
            'tipo'               => $request->tipo,
            'contenido'          => $request->contenido,
        ]);

        return redirect()
            ->route('pie.informes.index')
            ->with('success', 'Informe registrado correctamente.');
    }

    /**
     * Mostrar informe PIE
     */
    public function show($id)
    {
        $informePIE = InformePIE::with([
            'estudiante.alumno.curso'
        ])->findOrFail($id);

        $this->validarEstablecimiento($informePIE);

        return view('modulos.pie.informes.show', compact('informePIE'));
    }
    /**
     * Validación de establecimiento
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] El modelo ".get_class($modelo)." (ID: {$modelo->id}) no tiene establecimiento_id definido.");
                return;
            } else {
                abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
            }
        }

        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
