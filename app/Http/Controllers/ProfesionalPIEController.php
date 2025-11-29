<?php

namespace App\Http\Controllers;

use App\Models\ProfesionalPIE;
use App\Models\TipoProfesionalPIE;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class ProfesionalPIEController extends Controller
{
    /**
     * Listado de profesionales PIE
     */
    public function index()
    {
        if (!canAccess('pie', 'view')) {
            abort(403, 'No tienes permisos para ver profesionales PIE.');
        }

        $establecimiento_id = session('establecimiento_id');

        $profesionales = ProfesionalPIE::with(['funcionario', 'tipo'])
            ->where('establecimiento_id', $establecimiento_id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('modulos.pie.profesionales.index', compact('profesionales'));
    }

    /**
     * Formulario para crear un profesional PIE
     */
    public function create()
    {
        if (!canAccess('pie', 'create')) {
            abort(403, 'No tienes permisos para agregar profesionales PIE.');
        }

        $establecimiento_id = session('establecimiento_id');

        $tipos = TipoProfesionalPIE::orderBy('nombre')->get();

        $funcionarios = Funcionario::activos()
            ->where('establecimiento_id', $establecimiento_id)
            ->orderBy('apellido_paterno')
            ->orderBy('nombre')
            ->get();

        return view('modulos.pie.profesionales.create', compact('tipos', 'funcionarios'));
    }

    /**
     * Mostrar detalle del profesional PIE
     */
    public function show($id)
    {
        if (!canAccess('pie', 'view')) {
            abort(403, 'No tienes permisos para ver profesionales PIE.');
        }

        $profesional = ProfesionalPIE::with(['funcionario.cargo', 'tipo'])
            ->findOrFail($id);

        // Validar multicolegio
        if ($profesional->establecimiento_id !== session('establecimiento_id')) {
            abort(403, 'Acceso denegado: pertenece a otro establecimiento.');
        }

        return view('modulos.pie.profesionales.show', compact('profesional'));
    }

    /**
     * Guardar un nuevo profesional del PIE
     */
    public function store(Request $request)
    {
        if (!canAccess('pie', 'create')) {
            abort(403, 'No tienes permisos para agregar profesionales PIE.');
        }

        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'tipo_id'        => 'required|exists:tipos_profesional_pie,id',
        ]);

        // Evitar duplicados
        $yaExiste = ProfesionalPIE::where('funcionario_id', $request->funcionario_id)
            ->where('establecimiento_id', session('establecimiento_id'))
            ->exists();

        if ($yaExiste) {
            return back()->withErrors([
                'funcionario_id' => 'Este funcionario ya está registrado como profesional PIE.'
            ])->withInput();
        }

        $profesional = ProfesionalPIE::create([
            'establecimiento_id' => session('establecimiento_id'),
            'funcionario_id'     => $request->funcionario_id,
            'tipo_id'            => $request->tipo_id,
        ]);

        /* ============================================================
        AUDITORÍA: CREAR PROFESIONAL PIE
        ============================================================ */
        logAuditoria(
            accion: 'create',
            modulo: 'pie_profesionales',
            detalle: 'Se agregó al profesional PIE ID ' . $profesional->id,
            establecimiento_id: session('establecimiento_id')
        );

        return redirect()
            ->route('pie.profesionales.index')
            ->with('success', 'Profesional PIE agregado correctamente.');
    }

    /**
     * Eliminar profesional PIE
     */
    public function destroy(ProfesionalPIE $profesionalPIE)
    {
        if (!canAccess('pie', 'delete')) {
            abort(403, 'No tienes permisos para eliminar profesionales PIE.');
        }

        // Validación multicolegio
        if ($profesionalPIE->establecimiento_id !== session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        // Opcional: evitar eliminar si tiene intervenciones PIE
        // if ($profesionalPIE->intervenciones()->count() > 0) {
        //     return back()->withErrors([
        //         'error' => 'No se puede eliminar este profesional porque tiene intervenciones registradas.'
        //     ]);
        // }

        $profesionalPIE->delete();

        /* ============================================================
        AUDITORÍA: ELIMINAR PROFESIONAL PIE
        ============================================================ */
        logAuditoria(
            accion: 'delete',
            modulo: 'pie_profesionales',
            detalle: 'Se eliminó al profesional PIE ID ' . $profesionalPIE->id,
            establecimiento_id: session('establecimiento_id')
        );

        return back()->with('success', 'Profesional PIE eliminado.');
    }
}
