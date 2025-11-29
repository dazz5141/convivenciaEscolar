<?php

namespace App\Http\Controllers;

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
        if (!canAccess('citaciones','view')) {
            abort(403,'No tienes permisos para ver citaciones.');
        }

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
     * Formulario de creación
     */
    public function create()
    {
        if (!canAccess('citaciones','create')) {
            abort(403,'No tienes permisos para crear citaciones.');
        }

        $estados = EstadoCitacion::orderBy('nombre')->get();

        return view('modulos.inspectoria.citaciones.create', compact('estados'));
    }


    /**
     * Guardar citación
     */
    public function store(Request $request)
    {
        if (!canAccess('citaciones','create')) {
            abort(403,'No tienes permisos para crear citaciones.');
        }

        $request->validate([
            'alumno_id'      => 'required|exists:alumnos,id',
            'apoderado_id'   => 'nullable|exists:apoderados,id',
            'fecha_citacion' => 'required|date',
            'estado_id'      => 'required|exists:estados_citacion,id',
            'motivo'         => 'nullable|string',
            'observaciones'  => 'nullable|string',
        ]);

        $establecimientoId = session('establecimiento_id');

        // Crear registro (antes no se guardaba en variable)
        $citacion = CitacionApoderado::create([
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
        | NOTIFICACIONES AUTOMÁTICAS
        |--------------------------------------------------------------------------
        */

        $alumno = Alumno::find($request->alumno_id);

        $rolesDestino = [3, 8]; // Inspector General, Convivencia Escolar

        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        $mensaje = "Nueva citación registrada para {$alumno->nombre_completo} con fecha {$request->fecha_citacion}.";

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

        /* ===========================================
        AUDITORÍA - CREAR CITACIÓN
        =========================================== */
        logAuditoria(
            accion: 'create',
            modulo: 'citaciones',
            detalle: 'Se registró una citación ID ' . $citacion->id,
            establecimiento_id: $citacion->establecimiento_id
        );

        return redirect()
            ->route('inspectoria.citaciones.index')
            ->with('success', 'Citación registrada correctamente.');
    }


    /**
     * Mostrar citación
     */
    public function show(CitacionApoderado $citacion)
    {
        if (!canAccess('citaciones','view')) {
            abort(403,'No tienes permisos para ver citaciones.');
        }

        $this->validarEstablecimiento($citacion);

        return view('modulos.inspectoria.citaciones.show', compact('citacion'));
    }


    /**
     * Editar citación
     */
    public function edit(CitacionApoderado $citacion)
    {
        if (!canAccess('citaciones','edit')) {
            abort(403,'No tienes permisos para editar citaciones.');
        }

        $this->validarEstablecimiento($citacion);

        $estados = EstadoCitacion::orderBy('nombre')->get();

        return view('modulos.inspectoria.citaciones.edit', compact('citacion', 'estados'));
    }


    /**
     * Actualizar citación
     */
    public function update(Request $request, CitacionApoderado $citacion)
    {
        if (!canAccess('citaciones','edit')) {
            abort(403,'No tienes permisos para editar citaciones.');
        }

        $this->validarEstablecimiento($citacion);

        $request->validate([
            'fecha_citacion' => 'required|date',
            'estado_id'      => 'required|exists:estados_citacion,id',
            'motivo'         => 'nullable|string',
            'observaciones'  => 'nullable|string',
            'apoderado_id'   => 'nullable|exists:apoderados,id',
        ]);

        $citacion->update([
            'fecha_citacion' => $request->fecha_citacion,
            'estado_id'      => $request->estado_id,
            'motivo'         => $request->motivo,
            'observaciones'  => $request->observaciones,
            'apoderado_id'   => $request->apoderado_id,
        ]);

        /* ===========================================
        AUDITORÍA - ACTUALIZAR CITACIÓN
        =========================================== */
        logAuditoria(
            accion: 'update',
            modulo: 'citaciones',
            detalle: 'Se actualizó la citación ID ' . $citacion->id,
            establecimiento_id: $citacion->establecimiento_id
        );

        return redirect()
            ->route('inspectoria.citaciones.index')
            ->with('success', 'Citación actualizada correctamente.');
    }



    /**
     * Seguridad multicolegio
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            abort(403,'El registro no tiene establecimiento asignado.');
        }

        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403,'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
