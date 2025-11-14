<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AsistenciaEvento;
use App\Models\TipoAsistencia;
use Illuminate\Support\Facades\Auth;

class AsistenciaEventoController extends Controller
{
    /**
     * Listado de registros de asistencia / atrasos
     */
    public function index()
    {
        $establecimiento = session('establecimiento_id');

        $eventos = AsistenciaEvento::with(['alumno.curso', 'tipo', 'funcionario'])
            ->delColegio($establecimiento)
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('modulos.inspectoria.asistencia.index', compact('eventos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $tipos = TipoAsistencia::orderBy('nombre')->get();

        return view('modulos.inspectoria.asistencia.create', compact('tipos'));
    }

    /**
     * Guardar nuevo evento de asistencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha'      => 'required|date',
            'hora'       => 'nullable|date_format:H:i',
            'tipo_id'    => 'required|exists:tipos_asistencia,id',
            'alumno_id'  => 'required|exists:alumnos,id',
        ]);

        AsistenciaEvento::create([
            'fecha'              => $request->fecha,
            'hora'               => $request->hora,
            'tipo_id'            => $request->tipo_id,
            'observaciones'      => $request->observaciones,
            'alumno_id'          => $request->alumno_id,
            'registrado_por'     => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()
            ->route('inspectoria.asistencia.index')
            ->with('success', 'Registro de asistencia guardado.');
    }

    /**
     * Ver detalle de un registro de asistencia
     */
    public function show(AsistenciaEvento $evento)
    {
        $this->validarEstablecimiento($evento);

        return view('modulos.inspectoria.asistencia.show', compact('evento'));
    }

    /**
     * Formulario de edición
     */
    public function edit(AsistenciaEvento $evento)
    {
        $this->validarEstablecimiento($evento);

        $tipos = TipoAsistencia::orderBy('nombre')->get();

        return view('modulos.inspectoria.asistencia.edit', compact('evento', 'tipos'));
    }

    /**
     * Actualizar evento de asistencia
     */
    public function update(Request $request, AsistenciaEvento $evento)
    {
        $this->validarEstablecimiento($evento);

        $request->validate([
            'hora'          => 'nullable|date_format:H:i',
            'tipo_id'       => 'required|exists:tipos_asistencia,id',
            'observaciones' => 'nullable|string',
        ]);

        // Campos NO editables:
        // - fecha
        // - alumno_id
        // - registrado_por

        $evento->update([
            'hora'          => $request->hora,
            'tipo_id'       => $request->tipo_id,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('inspectoria.asistencia.index')
            ->with('success', 'Registro actualizado.');
    }

    /**
     * Validar que pertenezca al establecimiento actual
     */
    private function validarEstablecimiento($modelo)
    {
        if ($modelo->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'No autorizado para ver este registro.');
        }
    }
}
