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

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES – EQUIPO PIE
        |--------------------------------------------------------------------------
        */
        $establecimientoId = session('establecimiento_id');

        // Obtener profesionales PIE con usuario válido
        $profesionales = ProfesionalPIE::where('establecimiento_id', $establecimientoId)
            ->with('funcionario.usuario')
            ->get();

        foreach ($profesionales as $pro) {
            if ($pro->funcionario && $pro->funcionario->usuario) {

                Notificacion::create([
                    'tipo'              => 'pie_derivacion',
                    'mensaje'           => "Nueva derivación PIE registrada para estudiante ID {$request->estudiante_pie_id}.",
                    'usuario_id'        => $pro->funcionario->usuario->id, // receptor
                    'origen_id'         => $derivacion->id,
                    'origen_model'      => DerivacionPIE::class,
                    'establecimiento_id'=> $establecimientoId,
                ]);

            }
        }

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

        // Cargar relaciones necesarias (anidado: estudiante -> alumno)
        $derivacionPIE->load([
            'estudiante.alumno'
        ]);

        // Alias corto para mantener compatibilidad con la vista
        $derivacion = $derivacionPIE;

        return view('modulos.pie.derivaciones.show', compact('derivacionPIE'));
    }

    /**
     * Validación de establecimiento
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        // Si no tiene establecimiento definido
        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] El modelo ".get_class($modelo)." (ID: {$modelo->id}) no tiene establecimiento_id definido.");
                return;
            } else {
                abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
            }
        }

        // Si pertenece a otro establecimiento
        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
