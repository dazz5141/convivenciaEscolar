<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\EstudiantePIE;
use Illuminate\Http\Request;

class EstudiantePIEController extends Controller
{
    /**
     * Listado de estudiantes PIE del establecimiento actual
     */
    public function index()
    {
        $establecimiento_id = session('establecimiento_id');

        $estudiantes = EstudiantePIE::with('alumno')
        ->where('establecimiento_id', $establecimiento_id)
        ->whereNull('fecha_egreso') // SOLO ACTIVOS
        ->orderBy('fecha_ingreso', 'desc')
        ->paginate(20);

        return view('modulos.pie.estudiantes.index', compact('estudiantes'));
    }

    /**
     * Formulario para agregar estudiante al PIE
     */
    public function create()
    {
        return view('modulos.pie.estudiantes.create');
    }

    /**
     * Guardar estudiante PIE
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'      => 'required|exists:alumnos,id|unique:estudiantes_pie,alumno_id',
            'fecha_ingreso'  => 'required|date',
            'diagnostico'    => 'nullable|string|max:255',
            'observaciones'  => 'nullable|string',
        ]);

        EstudiantePIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'alumno_id'          => $request->alumno_id,
            'fecha_ingreso'      => $request->fecha_ingreso,
            'diagnostico'        => $request->diagnostico,
            'observaciones'      => $request->observaciones,
        ]);

        return redirect()
            ->route('pie.estudiantes.index')
            ->with('success', 'Estudiante incorporado al PIE correctamente.');
    }

    /**
     * Mostrar ficha completa del estudiante PIE
     */
    public function show(EstudiantePIE $estudiantePIE)
    {
        $this->validarEstablecimiento($estudiantePIE);

        $estudiantePIE->load([
            'alumno',
            'intervenciones.tipo',
            'intervenciones.profesional.funcionario',
            'planes',
            'derivaciones',
            'informes',
        ]);

        return view('modulos.pie.estudiantes.show', compact('estudiantePIE'));
    }

    /**
     * Eliminar registro PIE
     */
    public function destroy(EstudiantePIE $estudiantePIE)
    {
        $this->validarEstablecimiento($estudiantePIE);

        $estudiantePIE->delete();

        return redirect()
            ->route('pie.estudiantes.index')
            ->with('success', 'El estudiante fue retirado del PIE.');
    }

    /**
     * Validar que el estudiante pertenece al establecimiento actual
     */
    private function validarEstablecimiento(EstudiantePIE $estudiantePIE)
    {
        if ($estudiantePIE->establecimiento_id !== session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }
    }

    /**
     * Egresar estudiante del PIE (asigna fecha_egreso)
     */
    public function egresar(Request $request, EstudiantePIE $estudiantePIE)
    {
        $this->validarEstablecimiento($estudiantePIE);

        $request->validate([
            'fecha_egreso' => 'required|date|after_or_equal:' . $estudiantePIE->fecha_ingreso,
            'observaciones_egreso' => 'nullable|string',
        ]);

        $estudiantePIE->update([
            'fecha_egreso' => $request->fecha_egreso,
            'observaciones' => $request->observaciones_egreso 
                ? ($estudiantePIE->observaciones . "\nEGRESO: " . $request->observaciones_egreso)
                : $estudiantePIE->observaciones,
        ]);

        return redirect()
            ->route('pie.estudiantes.show', $estudiantePIE)
            ->with('success', 'El estudiante fue egresado del Programa PIE correctamente.');
    }
}
