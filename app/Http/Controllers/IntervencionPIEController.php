<?php

namespace App\Http\Controllers;

use App\Models\IntervencionPIE;
use App\Models\TipoIntervencionPIE;
use App\Models\ProfesionalPIE;
use App\Models\EstudiantePIE;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class IntervencionPIEController extends Controller
{
    /**
     * Listado general de intervenciones PIE
     */
    public function index(Request $request)
    {
        // 🔐 Permiso
        if (!canAccess('intervenciones', 'view')) {
            abort(403, 'No tienes permisos para acceder a Intervenciones PIE.');
        }

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
        $query = IntervencionPIE::with([
            'estudiante.alumno',
            'tipo',
            'profesional'
        ])->where('establecimiento_id', $establecimiento_id);

        if ($request->filled('estudiante_pie_id')) {
            $query->where('estudiante_pie_id', $request->estudiante_pie_id);
        }

        if ($request->filled('tipo_intervencion_id')) {
            $query->where('tipo_intervencion_id', $request->tipo_intervencion_id);
        }

        $intervenciones = $query->orderBy('fecha', 'desc')->paginate(20);

        return view('modulos.pie.intervenciones.index', compact(
            'estudiantes',
            'tipos',
            'intervenciones'
        ));
    }

    /**
     * Crear intervención
     */
    public function create($estudiante_pie_id = null)
    {
        // 🔐 Permiso
        if (!canAccess('intervenciones', 'create')) {
            abort(403, 'No tienes permisos para registrar intervenciones PIE.');
        }

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
        // 🔐 Permiso
        if (!canAccess('intervenciones', 'create')) {
            abort(403, 'No tienes permisos para registrar intervenciones PIE.');
        }

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

        // Guardar intervención
        $intervencion = IntervencionPIE::create([
            'establecimiento_id'   => session('establecimiento_id'),
            'estudiante_pie_id'    => $request->estudiante_pie_id,
            'tipo_intervencion_id' => $request->tipo_intervencion_id,
            'profesional_id'       => $request->profesional_id,
            'fecha'                => $request->fecha,
            'detalle'              => $request->detalle,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIÓN PROFESIONAL - INTERVENCIÓN PIE
        |--------------------------------------------------------------------------
        */
        $profesional = ProfesionalPIE::with('funcionario.usuario')->find($request->profesional_id);

        if ($profesional &&
            $profesional->funcionario &&
            $profesional->funcionario->usuario) {

            Notificacion::create([
                'tipo'              => 'pie_intervencion',
                'mensaje'           => "Nueva intervención PIE registrada.",
                'usuario_id'        => $profesional->funcionario->usuario->id, // DESTINATARIO
                'origen_id'         => $intervencion->id,
                'origen_model'      => IntervencionPIE::class,
                'establecimiento_id'=> session('establecimiento_id'),
            ]);
        }

        return redirect()
            ->route('pie.intervenciones.index')
            ->with('success', 'Intervención registrada correctamente.');
    }

    /**
     * Mostrar intervención
     */
    public function show($id)
    {
        // 🔐 Permiso
        if (!canAccess('intervenciones', 'view')) {
            abort(403, 'No tienes permisos para ver intervenciones PIE.');
        }

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
