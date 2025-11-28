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
     * ============================================================
     * LISTADO
     * ============================================================
     */
    public function index()
    {
        if (!canAccess('conflictos_funcionarios','view')) {
            abort(403, 'No tienes permisos para ver conflictos entre funcionarios.');
        }

        $conflictos = ConflictoFuncionario::with([
            'funcionario1',
            'funcionario2',
            'registradoPor',
            'registradoPor.rol',
            'estado'
        ])
        ->delColegio(session('establecimiento_id'))
        ->orderBy('fecha', 'desc')
        ->paginate(15);

        // Cargar funcionario seleccionado si viene por filtro
        $funcionarioSeleccionado = null;
        if (request('funcionario_id')) {
            $funcionarioSeleccionado = Funcionario::find(request('funcionario_id'));
        }

        return view('modulos.ley-karin.conflictos-funcionarios.index', [
            'conflictos' => $conflictos,
            'funcionarioSeleccionado' => $funcionarioSeleccionado
        ]);
    }


    /**
     * ============================================================
     * FORMULARIO CREAR
     * ============================================================
     */
    public function create()
    {
        if (!canAccess('conflictos_funcionarios','create')) {
            abort(403, 'No tienes permisos para registrar conflictos entre funcionarios.');
        }

        $funcionarios = Funcionario::delColegio(session('establecimiento_id'))
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->get();

        $estados = EstadoConflictoFuncionario::all();

        return view('modulos.ley-karin.conflictos-funcionarios.create', compact('funcionarios', 'estados'));
    }


    /**
     * ============================================================
     * GUARDAR
     * ============================================================
     */
    public function store(Request $request)
    {
        if (!canAccess('conflictos_funcionarios','create')) {
            abort(403, 'No tienes permisos para crear registros.');
        }

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

        // MULTICOLEGIO: validar que ambos funcionarios pertenezcan al colegio
        $this->validarFuncionarioColegio($request->funcionario_1_id);
        $this->validarFuncionarioColegio($request->funcionario_2_id);

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

        return redirect()
            ->route('leykarin.conflictos-funcionarios.index')
            ->with('success', 'Conflicto registrado correctamente.');
    }


    /**
     * ============================================================
     * MOSTRAR
     * ============================================================
     */
    public function show(ConflictoFuncionario $conflictoFuncionario)
    {
        if (!canAccess('conflictos_funcionarios','view')) {
            abort(403, 'No tienes permisos para ver el detalle del conflicto.');
        }

        $this->validarEstablecimiento($conflictoFuncionario);

        return view('modulos.ley-karin.conflictos-funcionarios.show', [
            'conflicto' => $conflictoFuncionario
        ]);
    }


    /**
     * ============================================================
     * EDITAR
     * ============================================================
     */
    public function edit(ConflictoFuncionario $conflictoFuncionario)
    {
        if (!canAccess('conflictos_funcionarios','edit')) {
            abort(403, 'No tienes permisos para editar conflictos entre funcionarios.');
        }

        $this->validarEstablecimiento($conflictoFuncionario);

        $estados = EstadoConflictoFuncionario::all();

        return view('modulos.ley-karin.conflictos-funcionarios.edit', [
            'conflicto' => $conflictoFuncionario,
            'estados'   => $estados
        ]);
    }


    /**
     * ============================================================
     * ACTUALIZAR
     * ============================================================
     */
    public function update(Request $request, ConflictoFuncionario $conflictoFuncionario)
    {
        if (!canAccess('conflictos_funcionarios','edit')) {
            abort(403, 'No tienes permisos para actualizar registros.');
        }

        $this->validarEstablecimiento($conflictoFuncionario);

        $request->validate([
            'tipo_conflicto'    => 'nullable|string|max:100',
            'lugar_conflicto'   => 'nullable|string|max:150',
            'descripcion'       => 'required|string',
            'acuerdos_previos'  => 'nullable|string',
            'estado_id'         => 'nullable|exists:estados_conflicto_funcionario,id',
            'confidencial'      => 'nullable|boolean',
            'derivado_ley_karin'=> 'nullable|boolean',
        ]);

        $conflictoFuncionario->update([
            'tipo_conflicto'    => $request->tipo_conflicto,
            'lugar_conflicto'   => $request->lugar_conflicto,
            'descripcion'       => $request->descripcion,
            'acuerdos_previos'  => $request->acuerdos_previos,
            'estado_id'         => $request->estado_id,
            'confidencial'      => $request->confidencial ?? 0,
            'derivado_ley_karin'=> $request->derivado_ley_karin ?? 0,
        ]);

        return redirect()
            ->route('leykarin.conflictos-funcionarios.index')
            ->with('success', 'Conflicto actualizado correctamente.');
    }


    /**
     * ============================================================
     * VALIDAR QUE EL REGISTRO PERTENEZCA AL COLEGIO
     * ============================================================
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
        }

        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }


    /**
     * ============================================================
     * VALIDAR QUE EL FUNCIONARIO PERTENEZCA AL COLEGIO
     * ============================================================
     */
    private function validarFuncionarioColegio($funcionarioId)
    {
        $funcionario = Funcionario::find($funcionarioId);

        if (!$funcionario) {
            abort(422, 'Funcionario no encontrado.');
        }

        if ($funcionario->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'El funcionario seleccionado no pertenece al establecimiento actual.');
        }
    }
}
