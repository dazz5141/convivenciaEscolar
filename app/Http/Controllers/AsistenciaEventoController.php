<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsistenciaEvento;
use App\Models\TipoAsistencia;
use App\Models\Usuario;
use App\Models\Notificacion;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

class AsistenciaEventoController extends Controller
{
    const TIPO_ATRASO = 1; // ID del tipo "Atraso"

    /**
     * Listado de asistencia / atrasos
     */
    public function index(Request $request)
    {
        // ---------------------------------------------------
        // PERMISO: VER LISTADO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'view')) {
            abort(403, 'No tiene permiso para ver asistencia.');
        }

        $establecimiento = session('establecimiento_id');

        // Filtros dinámicos
        $query = AsistenciaEvento::with(['alumno.curso', 'tipo', 'funcionario'])
            ->delColegio($establecimiento);

        // Filtro por alumno
        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
        }

        // Filtro por tipo de asistencia
        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        $eventos = $query->orderBy('fecha', 'desc')
                        ->orderBy('hora', 'desc')
                        ->paginate(15);

        // Para mostrar el nombre en el buscador
        $alumnoSeleccionado = $request->filled('alumno_id')
            ? Alumno::find($request->alumno_id)
            : null;

        // Lista de tipos (Atraso, Inasistencia, Justificada, Retiro Anticipado)
        $tipos = TipoAsistencia::orderBy('nombre')->get();

        return view('modulos.inspectoria.asistencia.index', compact(
            'eventos',
            'tipos',
            'alumnoSeleccionado'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        // ---------------------------------------------------
        // PERMISO: CREAR EVENTO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'create')) {
            abort(403, 'No tiene permiso para registrar asistencia.');
        }

        $tipos = TipoAsistencia::orderBy('nombre')->get();

        return view('modulos.inspectoria.asistencia.create', compact('tipos'));
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        // ---------------------------------------------------
        // PERMISO: CREAR EVENTO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'create')) {
            abort(403, 'No tiene permiso para registrar asistencia.');
        }

        $request->validate([
            'fecha'      => 'required|date',
            'tipo_id'    => 'required|exists:tipos_asistencia,id',
            'alumno_id'  => 'required|exists:alumnos,id',
            'hora'       => 'nullable|date_format:H:i',
            'observaciones' => 'nullable|string'
        ]);

        // Si es ATRASO → hora OBLIGATORIA
        if ($request->tipo_id == self::TIPO_ATRASO) {
            $request->validate([
                'hora' => 'required|date_format:H:i'
            ]);
        }

        $establecimientoId = session('establecimiento_id');
        $alumno = Alumno::find($request->alumno_id);

        // ---------------------------------------------------
        // CREAR EVENTO
        // ---------------------------------------------------
        $asistencia = AsistenciaEvento::create([
            'fecha'              => $request->fecha,
            'hora'               => $request->hora,
            'tipo_id'            => $request->tipo_id,
            'observaciones'      => $request->observaciones,
            'alumno_id'          => $request->alumno_id,
            'registrado_por'     => Auth::user()->funcionario_id,
            'establecimiento_id' => $establecimientoId,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES AUTOMÁTICAS – ASISTENCIA / ATRASOS
        |--------------------------------------------------------------------------
        */

        $rolesDestino = [3, 8]; // Inspector general + Convivencia

        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        $tipoNombre = $asistencia->tipo->nombre ?? 'Evento';

        $mensaje = ($request->tipo_id == self::TIPO_ATRASO)
            ? "Atraso registrado para {$alumno->nombre_completo} a las {$request->hora}."
            : "Nuevo registro de asistencia ({$tipoNombre}) para {$alumno->nombre_completo}.";

        foreach ($usuariosDestino as $u) {
            Notificacion::create([
                'usuario_id'        => $u->id,
                'origen_id'         => $asistencia->id,
                'origen_model'      => AsistenciaEvento::class,
                'tipo'              => 'asistencia',
                'mensaje'           => $mensaje,
                'establecimiento_id'=> $establecimientoId,
            ]);
        }

        /* ===========================================
        AUDITORÍA - CREACIÓN DE ASISTENCIA / ATRASO
        =========================================== */
        logAuditoria(
            'create',
            'atrasos',
            'Se registró asistencia/atraso ID ' . $asistencia->id,
            $asistencia->establecimiento_id
        );

        return redirect()
            ->route('inspectoria.asistencia.index')
            ->with('success', 'Registro de asistencia ingresado correctamente.');
    }

    /**
     * Mostrar detalle de un evento
     */
    public function show(AsistenciaEvento $evento)
    {
        // ---------------------------------------------------
        // PERMISO: VER EVENTO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'view')) {
            abort(403, 'No tiene permiso para ver asistencia.');
        }

        $this->validarEstablecimiento($evento);

        return view('modulos.inspectoria.asistencia.show', compact('evento'));
    }

    /**
     * Formulario de edición
     */
    public function edit(AsistenciaEvento $evento)
    {
        // ---------------------------------------------------
        // PERMISO: EDITAR EVENTO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'edit')) {
            abort(403, 'No tiene permiso para editar asistencia.');
        }

        $this->validarEstablecimiento($evento);

        $tipos = TipoAsistencia::orderBy('nombre')->get();

        return view('modulos.inspectoria.asistencia.edit', compact('evento', 'tipos'));
    }

    /**
     * Actualizar evento
     */
    public function update(Request $request, AsistenciaEvento $evento)
    {
        // ---------------------------------------------------
        // PERMISO: EDITAR EVENTO
        // ---------------------------------------------------
        if (!canAccess('atrasos', 'edit')) {
            abort(403, 'No tiene permiso para editar asistencia.');
        }

        $this->validarEstablecimiento($evento);

        // Validación base
        $request->validate([
            'tipo_id'       => 'required|exists:tipos_asistencia,id',
            'hora'          => 'nullable',
            'observaciones' => 'nullable|string'
        ]);

        // Normalizar hora (acepta "08:10:00" y lo convierte a "08:10")
        $hora = null;
        if ($request->hora) {
            try {
                $hora = \Carbon\Carbon::parse($request->hora)->format('H:i');
            } catch (\Exception $e) {
                return back()
                    ->withErrors(['hora' => 'Formato de hora inválido'])
                    ->withInput();
            }
        }

        // Validación especial SOLO si es ATRASO
        if ($request->tipo_id == self::TIPO_ATRASO && !$hora) {
            return back()
                ->withErrors(['hora' => 'La hora es obligatoria para los atrasos.'])
                ->withInput();
        }

        // Guardar cambios
        $evento->update([
            'tipo_id'       => $request->tipo_id,
            'hora'          => $hora,
            'observaciones' => $request->observaciones,
        ]);

        /* ===========================================
        AUDITORÍA - ACTUALIZACIÓN DE ASISTENCIA
        =========================================== */
        logAuditoria(
            'update',
            'atrasos',
            'Se actualizó el registro de asistencia ID ' . $evento->id,
            $evento->establecimiento_id
        );

        return redirect()
            ->route('inspectoria.asistencia.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Verifica que el registro pertenezca al establecimiento actual
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
