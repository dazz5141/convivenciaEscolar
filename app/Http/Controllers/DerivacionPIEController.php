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
        // =============================
        // PERMISO: VER
        // =============================
        if (!canAccess('pie','view')) {
            abort(403, 'No tienes permisos para ver derivaciones PIE.');
        }

        $establecimiento_id = session('establecimiento_id');

        // Estudiantes PIE del establecimiento
        $estudiantes = EstudiantePIE::with('alumno')
            ->where('establecimiento_id', $establecimiento_id)
            ->leftJoin('alumnos', 'alumnos.id', '=', 'estudiantes_pie.alumno_id')
            ->select('estudiantes_pie.*')
            ->orderBy(\DB::raw("CONCAT(alumnos.apellido_paterno, ' ', alumnos.apellido_materno, ' ', alumnos.nombre)"))
            ->get();

        // Listado de derivaciones con filtro por estudiante
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
     * Formulario de creación
     */
    public function create($estudiante_pie_id = null)
    {
        // =============================
        // PERMISO: CREAR
        // =============================
        if (!canAccess('pie','create')) {
            abort(403, 'No tienes permisos para crear derivaciones PIE.');
        }

        return view('modulos.pie.derivaciones.create', compact('estudiante_pie_id'));
    }


    /**
     * Guardar derivación
     */
    public function store(Request $request)
    {
        // =============================
        // PERMISO: CREAR
        // =============================
        if (!canAccess('pie','create')) {
            abort(403, 'No tienes permisos para registrar derivaciones PIE.');
        }

        $request->validate([
            'estudiante_pie_id' => 'required|exists:estudiantes_pie,id',
            'fecha'             => 'required|date',
            'destino'           => 'required|string|max:150',
            'motivo'            => 'nullable|string',
            'estado'            => 'nullable|string|max:60',
        ]);

        // Validar multicolegio del estudiante
        $this->validarEstablecimiento(
            EstudiantePIE::findOrFail($request->estudiante_pie_id)
        );

        // Crear derivación
        $derivacion = DerivacionPIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'estudiante_pie_id'  => $request->estudiante_pie_id,
            'fecha'              => $request->fecha,
            'destino'            => $request->destino,
            'motivo'             => $request->motivo,
            'estado'             => $request->estado,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES – (DESACTIVADAS)
        |--------------------------------------------------------------------------
        | Si más adelante se define un profesional asignado al estudiante PIE,
        | puedes activar este bloque nuevamente.
        */

        return redirect()
            ->route('pie.derivaciones.index')
            ->with('success', 'Derivación registrada correctamente.');
    }


    /**
     * Mostrar derivación individual
     */
    public function show(DerivacionPIE $derivacionPIE)
    {
        // =============================
        // PERMISO: VER
        // =============================
        if (!canAccess('pie','view')) {
            abort(403, 'No tienes permisos para ver derivaciones PIE.');
        }

        // Validar establecimiento
        $this->validarEstablecimiento($derivacionPIE);

        // Cargar relaciones necesarias
        $derivacionPIE->load(['estudiante.alumno.curso']);

        return view('modulos.pie.derivaciones.show', compact('derivacionPIE'));
    }


    /**
     * Validación multicolegio
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        // Si no tiene establecimiento definido
        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] El modelo ".get_class($modelo)." (ID {$modelo->id}) no tiene establecimiento_id.");
                return;
            }
            abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
        }

        // Si pertenece a otro establecimiento
        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
