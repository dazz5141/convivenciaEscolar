<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ConflictoFuncionario;
use App\Models\EstadoConflictoFuncionario;
use App\Models\Funcionario;

class ConflictoFuncionarioController extends Controller
{
    /**
     * Listado
     */
    public function index()
    {
        $conflictos = ConflictoFuncionario::with([
            'funcionario1',
            'funcionario2',
            'registradoPor',
            'estado'
        ])
        ->delColegio(session('establecimiento_id'))
        ->orderBy('fecha', 'desc')
        ->paginate(15);

        return view('modulos.ley-karin.conflictos-funcionarios.index', compact('conflictos'));
    }

    /**
     * Crear
     */
    public function create()
    {
        $funcionarios = Funcionario::delColegio(session('establecimiento_id'))
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->get();
            
        $estados = EstadoConflictoFuncionario::all();

        return view('modulos.ley-karin.conflictos-funcionarios.create', compact('funcionarios', 'estados'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha'               => 'required|date',
            'funcionario_1_id'    => 'required|exists:funcionarios,id',
            'funcionario_2_id'    => 'required|exists:funcionarios,id|different:funcionario_1_id',
            'tipo_conflicto'      => 'nullable|string|max:100',
            'lugar_conflicto'     => 'nullable|string|max:150',
            'descripcion'         => 'required|string',
            'acuerdos_previos'    => 'nullable|string',
            'estado_id'           => 'nullable|exists:estados_conflicto_funcionario,id',
            'confidencial'        => 'nullable|boolean',
        ]);

        ConflictoFuncionario::create([
            'fecha'              => $request->fecha,
            'funcionario_1_id'   => $request->funcionario_1_id,
            'funcionario_2_id'   => $request->funcionario_2_id,
            'registrado_por_id'  => auth()->id(),
            'tipo_conflicto'     => $request->tipo_conflicto,
            'lugar_conflicto'    => $request->lugar_conflicto,
            'descripcion'        => $request->descripcion,
            'acuerdos_previos'   => $request->acuerdos_previos,
            'estado_id'          => $request->estado_id,
            'confidencial'       => $request->confidencial ?? 0,
            'derivado_ley_karin' => 0,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()->route('leykarin.conflictos-funcionarios.index')
            ->with('success', 'Conflicto registrado correctamente.');
    }

    /**
     * Mostrar detalle
     */
    public function show(ConflictoFuncionario $conflictoFuncionario)
    {
        $this->validarEstablecimiento($conflictoFuncionario);

        return view('modulos.ley-karin.conflictos-funcionarios.show', [
            'conflicto' => $conflictoFuncionario
        ]);
    }

    /**
     * Editar
     */
    public function edit(ConflictoFuncionario $conflictoFuncionario)
    {
        $this->validarEstablecimiento($conflictoFuncionario);

        $estados = EstadoConflictoFuncionario::all();
        return view('modulos.ley-karin.conflictos-funcionarios.edit', [
            'conflicto' => $conflictoFuncionario,
            'estados'   => $estados
        ]);
    }

    /**
     * Actualizar
     */
    public function update(Request $request, ConflictoFuncionario $conflictoFuncionario)
    {
        $this->validarEstablecimiento($conflictoFuncionario);

        $request->validate([
            'tipo_conflicto'     => 'nullable|string|max:100',
            'lugar_conflicto'    => 'nullable|string|max:150',
            'descripcion'        => 'required|string',
            'acuerdos_previos'   => 'nullable|string',
            'estado_id'          => 'nullable|exists:estados_conflicto_funcionario,id',
        ]);

        $conflictoFuncionario->update([
            'tipo_conflicto'    => $request->tipo_conflicto,
            'lugar_conflicto'   => $request->lugar_conflicto,
            'descripcion'       => $request->descripcion,
            'acuerdos_previos'  => $request->acuerdos_previos,
            'estado_id'         => $request->estado_id,
        ]);

        return redirect()->route('leykarin.conflictos-funcionarios.index')
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
