<?php

namespace App\Http\Controllers;

use App\Models\IntervencionPIE;
use App\Models\TipoIntervencionPIE;
use App\Models\ProfesionalPIE;
use App\Models\EstudiantePIE;
use Illuminate\Http\Request;

class IntervencionPIEController extends Controller
{
    /**
     * Listado general de intervenciones PIE
     */
    public function index(Request $request)
    {
        $establecimiento_id = session('establecimiento_id');

        // Listado de estudiantes PIE
        $estudiantes = EstudiantePIE::select('estudiantes_pie.*')
            ->join('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->where('estudiantes_pie.establecimiento_id', $establecimiento_id)
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->with('alumno')
            ->get();

        // Tipos de intervención
        $tipos = TipoIntervencionPIE::orderBy('nombre')->get();

        // Filtrado de intervenciones
        $query = IntervencionPIE::with(['estudiante.alumno', 'tipo', 'profesional'])
            ->where('establecimiento_id', $establecimiento_id);

        if ($request->filled('estudiante_pie_id')) {
            $query->where('estudiante_pie_id', $request->estudiante_pie_id);
        }

        if ($request->filled('tipo_intervencion_id')) {
            $query->where('tipo_intervencion_id', $request->tipo_intervencion_id);
        }

        $intervenciones = $query->paginate(20);

        return view('modulos.pie.intervenciones.index', compact(
            'estudiantes',
            'tipos',
            'intervenciones'
        ));
    }

    /**
     * Crear intervención desde ficha PIE
     */
    public function create($estudiante_pie_id = null)
    {
        $establecimiento_id = session('establecimiento_id');

        // Estudiantes PIE del establecimiento
        $estudiantesPie = EstudiantePIE::select('estudiantes_pie.*')
            ->join('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->where('estudiantes_pie.establecimiento_id', $establecimiento_id)
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->with('alumno.curso')
            ->get();

        // Tipos de intervención
        $tipos = TipoIntervencionPIE::orderBy('nombre')->get();

        // Profesionales PIE
        $profesionales = ProfesionalPIE::with('funcionario')
            ->where('establecimiento_id', $establecimiento_id)
            ->orderByRaw("(SELECT apellido_paterno FROM funcionarios WHERE funcionarios.id = funcionario_id) ASC")
            ->get();

        return view('modulos.pie.intervenciones.create', compact(
            'estudiante_pie_id',
            'estudiantesPie',
            'tipos',
            'profesionales'
        ));
    }

    /**
     * Guardar intervención
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_pie_id'      => 'required|exists:estudiantes_pie,id',
            'tipo_intervencion_id'   => 'required|exists:tipos_intervencion_pie,id',
            'profesional_id'         => 'required|exists:profesionales_pie,id',
            'fecha'                  => 'required|date',
            'detalle'                => 'nullable|string',
        ]);

        // Validar establecimiento
        $this->validarEstablecimiento(
            EstudiantePIE::findOrFail($request->estudiante_pie_id)
        );

        IntervencionPIE::create([
            'establecimiento_id'   => session('establecimiento_id'),
            'estudiante_pie_id'    => $request->estudiante_pie_id,
            'tipo_intervencion_id' => $request->tipo_intervencion_id,
            'profesional_id'       => $request->profesional_id,
            'fecha'                => $request->fecha,
            'detalle'              => $request->detalle,
        ]);

        return redirect()
            ->route('pie.intervenciones.index')
            ->with('success', 'Intervención registrada correctamente.');
    }

    /**
     * Mostrar intervención
     */
    public function show($id)
    {
        $intervencion = IntervencionPIE::with([
            'estudiante.alumno.curso',
            'tipo',
            'profesional.funcionario.cargo',
            'profesional.tipo'
        ])->findOrFail($id);

        $this->validarEstablecimiento($intervencion);

        return view('modulos.pie.intervenciones.show', compact('intervencion'));
    }

    /**
     * Validación multicolegio
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        // Si no hay valor en el modelo
        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                // 💡 En desarrollo: permitir y registrar advertencia
                \Log::warning("⚠️ [DEV] El modelo " . get_class($modelo) . " (ID: {$modelo->id}) no tiene establecimiento_id definido.");
                return;
            } else {
                // Bloquear si no tiene establecimiento_id
                abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
            }
        }

        // Si no coincide con la sesión actual
        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
