<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BitacoraIncidente;
use App\Models\BitacoraIncidenteObservacion;

class BitacoraIncidenteObservacionController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'observacion' => 'required|string|min:3',
        ]);

        BitacoraIncidente::findOrFail($id);

        BitacoraIncidenteObservacion::create([
            'incidente_id' => $id,
            'observacion' => $request->observacion,
            'agregado_por' => auth()->user()->funcionario_id 
                             ?? auth()->user()->id, // fallback si se maneja distinto
            'fecha_observacion' => now(),
        ]);

        return redirect()
            ->route('convivencia.bitacora.show', $id)
            ->with('success', 'Observación agregada correctamente.');
    }
}
