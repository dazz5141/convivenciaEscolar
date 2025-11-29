<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeguimientoEmocional;
use App\Models\SeguimientoEmocionalObservacion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SeguimientoEmocionalObservacionController extends Controller
{
    /**
     * Guardar una nueva observación dentro de un seguimiento emocional.
     */
    public function store(Request $request, $seguimiento_id)
    {
        $request->validate([
            'observacion' => 'required|string|min:3',
        ]);

        // Validar que el seguimiento exista y pertenezca al establecimiento del usuario
        $seguimiento = SeguimientoEmocional::findOrFail($seguimiento_id);

        // Crear la observación
        SeguimientoEmocionalObservacion::create([
            'seguimiento_id'   => $seguimiento->id,
            'observacion'      => $request->observacion,
            'agregado_por'     => Auth::user()->funcionario_id, // funcionario asociado al usuario autenticado
            'fecha_observacion'=> Carbon::now(),
        ]);

        // Auditoría
        logAuditoria(
            accion: 'create',
            modulo: 'seguimiento_emocional_observacion',
            detalle: 'Creó una observación para el seguimiento emocional ID ' . $seguimiento->id,
            establecimiento_id: session('establecimiento_id')
        );

        return back()->with('success', 'Observación agregada correctamente.');
    }
}
