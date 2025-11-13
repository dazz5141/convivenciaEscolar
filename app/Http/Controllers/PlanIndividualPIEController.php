<?php

namespace App\Http\Controllers;

use App\Models\PlanIndividualPIE;
use App\Models\EstudiantePIE;
use Illuminate\Http\Request;

class PlanIndividualPIEController extends Controller
{
    /**
     * Listado de planes PIE
     */
    public function index(Request $request)
    {
        $establecimiento_id = session('establecimiento_id');

        // Estudiantes PIE del establecimiento
        $estudiantes = EstudiantePIE::with('alumno')
            ->where('establecimiento_id', $establecimiento_id)
            ->orderBy(
                \DB::raw("CONCAT(alumnos.apellido_paterno, ' ', alumnos.apellido_materno, ' ', alumnos.nombre)")
            )
            ->leftJoin('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->select('estudiantes_pie.*')
            ->get();

        // Listado de planes
        $planes = PlanIndividualPIE::with(['estudiante.alumno'])
            ->where('establecimiento_id', $establecimiento_id)
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(20);

        return view('modulos.pie.planes.index', compact('estudiantes', 'planes'));
    }

    /**
     * Crear formulario de plan individual
     */
    public function create($estudiante_pie_id = null)
    {
        return view('modulos.pie.planes.create', compact('estudiante_pie_id'));
    }

    /**
     * Guardar plan individual
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_pie_id' => 'required|exists:estudiantes_pie,id',
            'fecha_inicio'      => 'required|date',
            'fecha_termino'     => 'nullable|date|after_or_equal:fecha_inicio',
            'objetivos'         => 'nullable|string',
            'evaluacion'        => 'nullable|string',
        ]);

        // Validación multicolegio
        $this->validarEstablecimiento(
            EstudiantePIE::findOrFail($request->estudiante_pie_id)
        );

        PlanIndividualPIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'estudiante_pie_id'  => $request->estudiante_pie_id,
            'fecha_inicio'       => $request->fecha_inicio,
            'fecha_termino'      => $request->fecha_termino,
            'objetivos'          => $request->objetivos,
            'evaluacion'         => $request->evaluacion,
        ]);

        return redirect()
            ->route('pie.planes.index')
            ->with('success', 'Plan individual registrado correctamente.');
    }

    /**
     * Mostrar detalle del plan individual
     */
    public function show($id)
    {
        // Buscar manualmente el plan (evita el problema del model binding)
        $planIndividualPIE = PlanIndividualPIE::with([
            'estudiante.alumno.curso'
        ])->findOrFail($id);

        // Validar que el plan pertenezca al establecimiento actual
        $this->validarEstablecimiento($planIndividualPIE);

        return view('modulos.pie.planes.show', compact('planIndividualPIE'));
    }

    /**
     * Validación multicolegio
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
