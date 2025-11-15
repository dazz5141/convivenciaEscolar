<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RetiroAnticipado;
use App\Models\Alumno;
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
            ->orderBy('hora', 'desc')
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
            'alumno_id'          => 'required|exists:alumnos,id',
            'fecha'              => 'required|date',
            'hora'               => 'required|date_format:H:i',
            'motivo'             => 'nullable|string|max:255',
            'apoderado_id'       => 'nullable|exists:apoderados,id',
            'nombre_retira'      => 'nullable|string|max:255',
            'run_retira'         => 'nullable|string|max:20',
            'parentesco_retira'  => 'nullable|string|max:50',
            'telefono_retira'    => 'nullable|string|max:20',
            'observaciones'      => 'nullable|string'
        ]);

        // Validación multicolegio del alumno
        $alumno = Alumno::with('curso')->findOrFail($request->alumno_id);

        if ($alumno->curso->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'El alumno no pertenece a este establecimiento.');
        }

        // Lógica híbrida:
        if (
            !$request->apoderado_id &&
            !$request->nombre_retira &&
            !$request->run_retira &&
            !$request->parentesco_retira
        ) {
            return back()
                ->withErrors(['Debe seleccionar un apoderado o ingresar los datos manuales.'])
                ->withInput();
        }

        // Si se selecciona apoderado → limpiar campos manuales
        if ($request->apoderado_id) {
            $nombre = null;
            $run = null;
            $parentesco = null;
            $telefono = null;
        } else {
            $request->merge(['apoderado_id' => null]);

            $nombre = $request->nombre_retira;
            $run = $request->run_retira;
            $parentesco = $request->parentesco_retira;
            $telefono = $request->telefono_retira;
        }

        RetiroAnticipado::create([
            'alumno_id'          => $request->alumno_id,
            'fecha'              => $request->fecha,
            'hora'               => $request->hora,
            'motivo'             => $request->motivo,
            'apoderado_id'       => $request->apoderado_id,
            'nombre_retira'      => $nombre,
            'run_retira'         => $run,
            'parentesco_retira'  => $parentesco,
            'telefono_retira'    => $telefono,
            'entregado_por'      => Auth::user()->funcionario_id,
            'observaciones'      => $request->observaciones,

            // 🔥 SOLUCIÓN REAL
            'establecimiento_id' => $alumno->curso->establecimiento_id,
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

        $retiro->load([
            'alumno.curso',
            'apoderado',
            'funcionarioEntrega'
        ]);

        return view('modulos.inspectoria.retiros.show', compact('retiro'));
    }

    /**
     * Formulario editar
     */
    public function edit(RetiroAnticipado $retiro)
    {
        $this->validarEstablecimiento($retiro);

        $retiro->load([
            'alumno.curso',
            'apoderado',
            'funcionarioEntrega'
        ]);

        return view('modulos.inspectoria.retiros.edit', compact('retiro'));
    }

    /**
     * Actualizar registro
     */
    public function update(Request $request, RetiroAnticipado $retiro)
    {
        $this->validarEstablecimiento($retiro);

        // Convertir formato 05:00:00 → 05:00 antes de validar
        if ($request->hora) {
            $request->merge([
                'hora' => substr($request->hora, 0, 5)
            ]);
        }

        // Validación corregida
        $request->validate([
            'hora'               => 'required|date_format:H:i',
            'motivo'             => 'nullable|string|max:255',
            'apoderado_id'       => 'nullable|exists:apoderados,id',
            'nombre_retira'      => 'nullable|string|max:255',
            'run_retira'         => 'nullable|string|max:20',
            'parentesco_retira'  => 'nullable|string|max:50',
            'telefono_retira'    => 'nullable|string|max:20',
            'observaciones'      => 'nullable|string',
        ]);

        // Lógica híbrida
        if ($request->apoderado_id) {
            $request->merge([
                'nombre_retira'     => null,
                'run_retira'        => null,
                'parentesco_retira' => null,
                'telefono_retira'   => null,
            ]);
        } else {
            $request->merge([
                'apoderado_id' => null
            ]);
        }

        // Actualizar
        $retiro->update($request->only([
            'hora',
            'motivo',
            'apoderado_id',
            'nombre_retira',
            'run_retira',
            'parentesco_retira',
            'telefono_retira',
            'observaciones',
        ]));

        return redirect()
            ->route('inspectoria.retiros.index')
            ->with('success', 'Retiro actualizado correctamente.');
    }

    /**
     * Seguridad multicolegio
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
