<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitacionApoderado;
use App\Models\EstadoCitacion;
use App\Models\Usuario;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

class CitacionApoderadoController extends Controller
{
    /**
     * Listado de citaciones
     */
    public function index()
    {
        $estados = EstadoCitacion::orderBy('nombre')->get();

        $citaciones = CitacionApoderado::with([
                'alumno.curso',
                'apoderado',
                'funcionario',
                'estado'
            ])
            ->delColegio(session('establecimiento_id'))
            ->orderBy('fecha_citacion', 'desc')
            ->paginate(15);

        return view('modulos.inspectoria.citaciones.index', compact('citaciones', 'estados'));
    }

    /**
     * Crear nueva citación
     */
    public function create()
    {
        $estados = EstadoCitacion::orderBy('nombre')->get();

        return view('modulos.inspectoria.citaciones.create', compact('estados'));
    }

    /**
     * Guardar citación
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'      => 'required|exists:alumnos,id',
            'apoderado_id'   => 'nullable|exists:apoderados,id',
            'fecha_citacion' => 'required|date',
            'estado_id'      => 'required|exists:estados_citacion,id',
            'motivo'         => 'nullable|string',
            'observaciones'  => 'nullable|string',
        ]);

        $establecimientoId = session('establecimiento_id');

        CitacionApoderado::create([
            'alumno_id'          => $request->alumno_id,
            'apoderado_id'       => $request->apoderado_id,
            'fecha_citacion'     => $request->fecha_citacion,
            'motivo'             => $request->motivo,
            'estado_id'          => $request->estado_id,
            'observaciones'      => $request->observaciones,
            'funcionario_id'     => Auth::user()->funcionario_id,
            'establecimiento_id' => $establecimientoId,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES AUTOMÁTICAS – CITACIONES
        |--------------------------------------------------------------------------
        |
        | Destinos habituales:
        | - Inspectoría
        | - Convivencia Escolar
        | - Inspector General
        |--------------------------------------------------------------------------
        */

        // Obtener alumno (para usar nombre completo)
        $alumno = Alumno::find($request->alumno_id);

        // Roles destino (ajustables según tu estructura)
        $rolesDestino = [3, 8]; 
        // 3 = Inspector General
        // 8 = Convivencia Escolar

        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        // Mensaje profesional
        $mensaje = "Nueva citación registrada para {$alumno->nombre_completo} "
                . "con fecha {$request->fecha_citacion}.";

        foreach ($usuariosDestino as $u) {
            Notificacion::create([
                'usuario_id'        => $u->id,
                'origen_id'         => $citacion->id,
                'origen_model'      => CitacionApoderado::class,
                'tipo'              => 'citacion',
                'mensaje'           => $mensaje,
                'establecimiento_id'=> $establecimientoId,
            ]);
        }

        return redirect()
            ->route('inspectoria.citaciones.index')
            ->with('success', 'Citacion registrada.');
    }

    /**
     * Mostrar citación
     */
    public function show(CitacionApoderado $citacion)
    {
        $this->validarEstablecimiento($citacion);

        return view('modulos.inspectoria.citaciones.show', compact('citacion'));
    }

    /**
     * Editar citación
     */
    public function edit(CitacionApoderado $citacion)
    {
        $this->validarEstablecimiento($citacion);

        $estados = EstadoCitacion::orderBy('nombre')->get();

        return view('modulos.inspectoria.citaciones.edit', compact('citacion', 'estados'));
    }

    /**
     * Actualizar citación
     */
    public function update(Request $request, CitacionApoderado $citacion)
    {
        $this->validarEstablecimiento($citacion);

        $request->validate([
            'fecha_citacion' => 'required|date',
            'estado_id'      => 'required|exists:estados_citacion,id',
            'motivo'         => 'nullable|string',
            'observaciones'  => 'nullable|string',
            'apoderado_id'   => 'nullable|exists:apoderados,id',
        ]);

        // Campos NO editables:
        // - alumno_id
        // - funcionario_id
        // - establecimiento_id

        $citacion->update([
            'fecha_citacion' => $request->fecha_citacion,
            'estado_id'      => $request->estado_id,
            'motivo'         => $request->motivo,
            'observaciones'  => $request->observaciones,
            'apoderado_id'   => $request->apoderado_id,
        ]);

        return redirect()
            ->route('inspectoria.citaciones.index')
            ->with('success', 'Citacion actualizada.');
    }

    /**
     * Validar establecimiento
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
