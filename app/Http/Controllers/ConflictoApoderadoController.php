<?php

namespace App\Http\Controllers;

use App\Models\ConflictoApoderado;
use App\Models\EstadoConflictoApoderado;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConflictoApoderadoController extends Controller
{
    // LISTADO
    public function index(Request $request)
    {
        $conflictos = ConflictoApoderado::with(['funcionario', 'registradoPor', 'estado'])
            ->delColegio(session('establecimiento_id'))
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('modulos.ley-karin.conflictos-apoderados.index', compact('conflictos'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        $estados = EstadoConflictoApoderado::all();

        return view('modulos.ley-karin.conflictos-apoderados.create', compact('estados'));
    }

    // REGISTRAR
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'funcionario_id' => 'required|exists:funcionarios,id',
            'apoderado_id' => 'nullable|exists:apoderados,id',
            'apoderado_nombre' => 'nullable|string|max:255',
            'apoderado_rut' => 'nullable|string|max:20',
            'tipo_conflicto' => 'nullable|string|max:100',
            'lugar_conflicto' => 'nullable|string|max:150',
            'descripcion' => 'required|string',
            'accion_tomada' => 'nullable|string',
            'estado_id' => 'nullable|exists:estados_conflicto_apoderado,id',
        ]);

        ConflictoApoderado::create([
            'fecha' => $request->fecha,
            'funcionario_id' => $request->funcionario_id,
            'registrado_por_id' => Auth::user()->funcionario_id,
            'apoderado_id' => $request->apoderado_id,
            'apoderado_nombre' => $request->apoderado_nombre,
            'apoderado_rut' => $request->apoderado_rut,
            'tipo_conflicto' => $request->tipo_conflicto,
            'lugar_conflicto' => $request->lugar_conflicto,
            'descripcion' => $request->descripcion,
            'accion_tomada' => $request->accion_tomada,
            'estado_id' => $request->estado_id,
            'confidencial' => $request->confidencial ?? 0,
            'derivado_ley_karin' => $request->derivado_ley_karin ?? 0,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()->route('leykarin.conflictos-apoderados.index')
            ->with('success', 'Conflicto registrado correctamente.');
    }

    // MOSTRAR
    public function show(ConflictoApoderado $conflictoApoderado)
    {
        return view('modulos.ley-karin.conflictos-apoderados.show', [
            'conflicto' => $conflictoApoderado
        ]);
    }

    // EDITAR
    public function edit(ConflictoApoderado $conflictoApoderado)
    {
        $estados = EstadoConflictoApoderado::all();

        return view('modulos.ley-karin.conflictos-apoderados.edit', [
            'conflicto' => $conflictoApoderado,
            'estados' => $estados
        ]);
    }

    // ACTUALIZAR
    public function update(Request $request, ConflictoApoderado $conflictoApoderado)
    {
        $request->validate([
            'tipo_conflicto' => 'nullable|string|max:100',
            'lugar_conflicto' => 'nullable|string|max:150',
            'descripcion' => 'required|string',
            'accion_tomada' => 'nullable|string',
            'estado_id' => 'nullable|exists:estados_conflicto_apoderado,id',
        ]);

        $conflictoApoderado->update([
            'tipo_conflicto' => $request->tipo_conflicto,
            'lugar_conflicto' => $request->lugar_conflicto,
            'descripcion' => $request->descripcion,
            'accion_tomada' => $request->accion_tomada,
            'estado_id' => $request->estado_id,
            'confidencial' => $request->confidencial ?? 0,
            'derivado_ley_karin' => $request->derivado_ley_karin ?? 0,
        ]);

        return redirect()->route('leykarin.conflictos-apoderados.index')
            ->with('success', 'Conflicto actualizado correctamente.');
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
