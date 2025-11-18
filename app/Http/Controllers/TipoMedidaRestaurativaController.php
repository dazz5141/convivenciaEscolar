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

        return back()->with('success', 'Tipo de medida creado');
    }

    public function destroy($id)
    {
        TipoMedidaRestaurativa::findOrFail($id)->delete();
        return back()->with('success', 'Tipo de medida eliminado');
    }
}
