<?php

namespace App\Http\Controllers;

use App\Models\TipoMedidaRestaurativa;
use Illuminate\Http\Request;

class TipoMedidaRestaurativaController extends Controller
{
    public function index()
    {
        $tipos = TipoMedidaRestaurativa::orderBy('nombre')->get();
        return view('modulos.medidas_restaurativas.tipos.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:150|unique:tipos_medida_restaurativa,nombre']);

        TipoMedidaRestaurativa::create([
            'nombre' => $request->nombre
        ]);

        // Auditoría
        logAuditoria(
            accion: 'create',
            modulo: 'tipo_medida_restaurativa',
            detalle: 'Creó un tipo de medida restaurativa: ' . $request->nombre,
            establecimiento_id: session('establecimiento_id')
        );
        
        return back()->with('success', 'Tipo de medida creado');
    }

    public function destroy($id)
    {
        TipoMedidaRestaurativa::findOrFail($id)->delete();

        // Auditoría
        logAuditoria(
            accion: 'delete',
            modulo: 'tipo_medida_restaurativa',
            detalle: 'Eliminó el tipo de medida restaurativa ID ' . $id,
            establecimiento_id: session('establecimiento_id')
        );

        return back()->with('success', 'Tipo de medida eliminado');
    }
}
