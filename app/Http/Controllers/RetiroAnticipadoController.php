<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RetiroAnticipado;
use Illuminate\Support\Facades\Auth;

class RetiroAnticipadoController extends Controller
{
    /**
     * Listado general
     */
    public function index()
    {
        $establecimiento = session('establecimiento_id');

        $retiros = RetiroAnticipado::with([
                'alumno.curso',
                'apoderado',
                'funcionarioEntrega'
            ])
            ->delColegio($establecimiento)
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('modulos.inspectoria.retiros.index', compact('retiros'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('modulos.inspectoria.retiros.create');
    }

    /**
     * Guardar retiro anticipado
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'    => 'required|exists:alumnos,id',
            'fecha'        => 'required|date',
            'hora'         => 'required|date_format:H:i',
            'motivo'       => 'nullable|string',
            'apoderado_id' => 'nullable|exists:apoderados,id',
            'observaciones'=> 'nullable|string'
        ]);

        RetiroAnticipado::create([
            'alumno_id'          => $request->alumno_id,
            'fecha'              => $request->fecha,
            'hora'               => $request->hora,
            'motivo'             => $request->motivo,
            'apoderado_id'       => $request->apoderado_id,
            'entregado_por'      => Auth::user()->funcionario_id,
            'observaciones'      => $request->observaciones,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()
            ->route('inspectoria.retiros.index')
            ->with('success', 'Retiro registrado correctamente.');
    }

    /**
     * Mostrar detalle
     */
    public function show(RetiroAnticipado $retiro)
    {
        $this->validarEstablecimiento($retiro);

        return view('modulos.inspectoria.retiros.show', compact('retiro'));
    }

    /**
     * Formulario editar
     */
    public function edit(RetiroAnticipado $retiro)
    {
        $this->validarEstablecimiento($retiro);

        return view('modulos.inspectoria.retiros.edit', compact('retiro'));
    }

    /**
     * Actualizar registro
     */
    public function update(Request $request, RetiroAnticipado $retiro)
    {
        $this->validarEstablecimiento($retiro);

        $request->validate([
            'hora'         => 'required|date_format:H:i',
            'motivo'       => 'nullable|string',
            'apoderado_id' => 'nullable|exists:apoderados,id',
            'observaciones'=> 'nullable|string',
        ]);

        // Campos NO editables:
        // - alumno_id
        // - fecha
        // - entregado_por
        // - establecimiento_id

        $retiro->update([
            'hora'         => $request->hora,
            'motivo'       => $request->motivo,
            'apoderado_id' => $request->apoderado_id,
            'observaciones'=> $request->observaciones,
        ]);

        return redirect()
            ->route('inspectoria.retiros.index')
            ->with('success', 'Retiro actualizado correctamente.');
    }

    /**
     * Seguridad multi-colegio
     */
    private function validarEstablecimiento($modelo)
    {
        if ($modelo->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }
    }
}
