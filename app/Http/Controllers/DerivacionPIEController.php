<?php

namespace App\Http\Controllers;

use App\Models\DerivacionPIE;
use App\Models\EstudiantePIE;
use Illuminate\Http\Request;

class DerivacionPIEController extends Controller
{

    /**
     * Listado de derivaciones del establecimiento
     */
    public function index(Request $request)
    {
        $establecimiento_id = session('establecimiento_id');

        // Estudiantes PIE del establecimiento
        $estudiantes = EstudiantePIE::with('alumno')
            ->where('establecimiento_id', $establecimiento_id)
            ->leftJoin('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->select('estudiantes_pie.*')
            ->orderBy(\DB::raw("CONCAT(alumnos.apellido_paterno, ' ', alumnos.apellido_materno, ' ', alumnos.nombre)"))
            ->get();

        // Filtro por estudiante
        $query = DerivacionPIE::with(['estudiante.alumno'])
            ->where('establecimiento_id', $establecimiento_id);

        if ($request->filled('estudiante_pie_id')) {
            $query->where('estudiante_pie_id', $request->estudiante_pie_id);
        }

        // Listado final
        $derivaciones = $query->orderBy('fecha', 'desc')->paginate(20);

        return view('modulos.pie.derivaciones.index', compact('estudiantes', 'derivaciones'));
    }

    /**
     * Crear derivación desde ficha del estudiante PIE
     */
    public function create($estudiante_pie_id = null)
    {
        return view('modulos.pie.derivaciones.create', compact('estudiante_pie_id'));
    }

    /**
     * Guardar derivación
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_pie_id' => 'required|exists:estudiantes_pie,id',
            'fecha'             => 'required|date',
            'destino'           => 'required|string|max:150',
            'motivo'            => 'nullable|string',
            'estado'            => 'nullable|string|max:60',
        ]);

        // Validar que el estudiante PIE pertenece al establecimiento
        $this->validarEstablecimiento(EstudiantePIE::findOrFail($request->estudiante_pie_id));

        DerivacionPIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'estudiante_pie_id'  => $request->estudiante_pie_id,
            'fecha'              => $request->fecha,
            'destino'            => $request->destino,
            'motivo'             => $request->motivo,
            'estado'             => $request->estado,
        ]);

        return redirect()
            ->route('pie.derivaciones.index')
            ->with('success', 'Derivación registrada correctamente.');
    }

    /**
     * Mostrar derivación individual
     */
    public function show(DerivacionPIE $derivacionPIE)
    {
        $this->validarEstablecimiento($derivacionPIE);

        $derivacionPIE->load(['estudiante.alumno']);

        return view('modulos.pie.derivaciones.show', compact('derivacionPIE'));
    }

    /**
     * Validación de establecimiento
     */
    private function validarEstablecimiento($modelo)
    {
        if ($modelo->establecimiento_id !== session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }
    }
}
