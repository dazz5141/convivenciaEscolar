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
        canAccess('pie-estudiantes', 'view');

        $establecimiento_id = session('establecimiento_id');

        $estudiantes = EstudiantePIE::with([
                'alumno.curso'
            ])
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
        canAccess('pie-estudiantes', 'create');

        return view('modulos.pie.estudiantes.create');
    }

    /**
     * Guardar estudiante PIE
     */
    public function store(Request $request)
    {
        canAccess('pie-estudiantes', 'create');

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
        canAccess('pie-estudiantes', 'view');

        $this->validarEstablecimiento($estudiantePIE);

        $estudiantePIE->load([
            'alumno.curso',
            'intervenciones.tipo',
            'intervenciones.profesional.funcionario',
        ]);

        return view('modulos.pie.estudiantes.show', compact('estudiantePIE'));
    }

    /**
     * Eliminar registro PIE
     */
    public function destroy(EstudiantePIE $estudiantePIE)
    {
        canAccess('pie-estudiantes', 'delete');

        $this->validarEstablecimiento($estudiantePIE);

        // ⚠️ Opcional: Si no quieres permitir eliminar con intervenciones:
        // if ($estudiantePIE->intervenciones()->exists()) {
        //     return back()->withErrors(['error' => 'No se puede eliminar este estudiante porque tiene intervenciones registradas.']);
        // }

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
     * Egresar estudiante del PIE
     */
    public function egresar(Request $request, EstudiantePIE $estudiantePIE)
    {
        canAccess('pie-estudiantes', 'egresar');

        $this->validarEstablecimiento($estudiantePIE);

        $request->validate([
            'fecha_egreso' => 'required|date|after_or_equal:' . $estudiantePIE->fecha_ingreso,
            'observaciones_egreso' => 'nullable|string',
        ]);

        // Registrar egreso y agregar nota al campo "observaciones"
        $observacionesNuevo = $estudiantePIE->observaciones;

        if ($request->observaciones_egreso) {
            $observacionesNuevo .= "\n---\nEGRESO (" . now()->format('d/m/Y H:i') . "): " 
                . $request->observaciones_egreso;
        }

        $estudiantePIE->update([
            'fecha_egreso' => $request->fecha_egreso,
            'observaciones' => $observacionesNuevo,
        ]);

        return redirect()
            ->route('pie.estudiantes.show', $estudiantePIE)
            ->with('success', 'El estudiante fue egresado del Programa PIE correctamente.');
    }
}
