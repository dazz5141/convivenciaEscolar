<?php

namespace App\Http\Controllers;

use App\Models\MedidaRestaurativa;
use App\Models\TipoMedidaRestaurativa;
use App\Models\EstadoCumplimiento;
use App\Models\BitacoraIncidente;
use App\Models\Funcionario;
use App\Models\Alumno; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedidaRestaurativaController extends Controller
{
    /**
     * LISTADO GENERAL
     */
    public function index(Request $request)
    {
        $establecimientoId = session('establecimiento_id');

        // Query base
        $query = MedidaRestaurativa::with([
                'incidente.alumno',
                'tipoMedida',
                'responsable',
                'cumplimiento'
            ])
            ->where('establecimiento_id', $establecimientoId);

        if ($request->filled('tipo_medida_id')) {
            $query->where('tipo_medida_id', $request->tipo_medida_id);
        }

        if ($request->filled('cumplimiento_estado_id')) {
            $query->where('cumplimiento_estado_id', $request->cumplimiento_estado_id);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fecha_inicio', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha_inicio', '<=', $request->hasta);
        }

        $medidas = $query->orderBy('created_at', 'desc')->paginate(20);

        $tipos = TipoMedidaRestaurativa::orderBy('nombre')->get();
        $estadosCumplimiento = EstadoCumplimiento::orderBy('nombre')->get();

        return view('modulos.convivencia-escolar.medidas-restaurativas.index', compact(
            'medidas',
            'tipos',
            'estadosCumplimiento'
        ));
    }

    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create(Request $request)
    {
        $establecimientoId = session('establecimiento_id');

        // Puede venir o no el incidente
        $incidente = null;

        if ($request->filled('incidente_id')) {
            $incidente = BitacoraIncidente::where('establecimiento_id', $establecimientoId)
                ->findOrFail($request->incidente_id);
        }

        $tipos = TipoMedidaRestaurativa::orderBy('nombre')->get();
        $estadosCumplimiento = EstadoCumplimiento::orderBy('nombre')->get();

        // Solo funcionarios del establecimiento
        $responsables = Funcionario::where('establecimiento_id', $establecimientoId)
            ->orderByRaw("CONCAT(nombre, ' ', apellido_paterno)")
            ->get();

        // listar alumnos del establecimiento si NO viene desde incidente
        $alumnos = null;

        if (!$incidente) {
            $alumnos = Alumno::whereHas('curso', function ($q) use ($establecimientoId) {
                    $q->where('establecimiento_id', $establecimientoId);
                })
                ->orderBy('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre')
                ->get();
        }

        return view('modulos.convivencia-escolar.medidas-restaurativas.create', compact(
            'incidente',
            'tipos',
            'estadosCumplimiento',
            'responsables',
            'alumnos'
        ));
    }

    /**
     * GUARDAR
     */
    public function store(Request $request)
    {
        $establecimientoId = session('establecimiento_id');

        // Validación
        $validated = $request->validate([
            'alumno_id'              => 'nullable|exists:alumnos,id',
            'incidente_id'           => 'nullable|exists:bitacora_incidentes,id',
            'tipo_medida_id'         => 'required|exists:tipos_medida_restaurativa,id',
            'responsable_id'         => 'required|exists:funcionarios,id',
            'cumplimiento_estado_id' => 'required|exists:estados_cumplimiento,id',
            'fecha_inicio'           => 'nullable|date',
            'fecha_fin'              => 'nullable|date|after_or_equal:fecha_inicio',
            'observaciones'          => 'nullable|string',
        ]);


        // Si viene desde incidente, se usa el alumno del incidente
        if (!empty($validated['incidente_id'])) {

            $incidente = BitacoraIncidente::find($validated['incidente_id']);

            // Seguridad: si no existe el incidente
            if (!$incidente) {
                return back()->withErrors('El incidente no existe.');
            }

            $validated['alumno_id'] = $incidente->alumno_id;
        }


        // Validación del alumno, debe existir uno
        if (empty($validated['alumno_id'])) {
            return back()
                ->withErrors('Debe seleccionar un alumno o un incidente asociado.')
                ->withInput();
        }


        // Crear medida
        MedidaRestaurativa::create([
            'alumno_id'              => $validated['alumno_id'],
            'incidente_id'           => $validated['incidente_id'] ?? null,
            'tipo_medida_id'         => $validated['tipo_medida_id'],
            'responsable_funcionario'=> $validated['responsable_id'],
            'cumplimiento_estado_id' => $validated['cumplimiento_estado_id'],
            'fecha_inicio'           => $validated['fecha_inicio'],
            'fecha_fin'              => $validated['fecha_fin'],
            'observaciones'          => $validated['observaciones'],
            'establecimiento_id'     => $establecimientoId,
        ]);

        return redirect()
            ->route('convivencia.medidas.index')
            ->with('success', 'Medida restaurativa registrada correctamente.');
    }

    /**
     * DETALLE
     */
    public function show($id)
    {
        $establecimientoId = session('establecimiento_id');

        $medida = MedidaRestaurativa::with(['incidente', 'tipoMedida', 'responsable', 'cumplimiento'])
            ->where('establecimiento_id', $establecimientoId)
            ->findOrFail($id);

        return view('modulos.convivencia-escolar.medidas-restaurativas.show', compact('medida'));
    }

    /**
     * EDITAR
     */
    public function edit($id)
    {
        $establecimientoId = session('establecimiento_id');

        $medida = MedidaRestaurativa::where('establecimiento_id', $establecimientoId)
            ->findOrFail($id);

        $tipos = TipoMedidaRestaurativa::orderBy('nombre')->get();
        $estadosCumplimiento = EstadoCumplimiento::orderBy('nombre')->get();

        $responsables = Funcionario::where('establecimiento_id', $establecimientoId)
            ->orderByRaw("CONCAT(nombre, ' ', apellido_paterno)")
            ->get();

        return view('modulos.convivencia-escolar.medidas-restaurativas.edit', compact(
            'medida',
            'tipos',
            'estadosCumplimiento',
            'responsables'
        ));
    }

    /**
     * ACTUALIZAR
     */
    public function update(Request $request, $id)
    {
        $establecimientoId = session('establecimiento_id');

        // Validación de campos enviados desde el form
        $validated = $request->validate([
            'tipo_medida_id'         => 'required|exists:tipos_medida_restaurativa,id',
            'responsable_id'         => 'required|exists:funcionarios,id',
            'cumplimiento_estado_id' => 'required|exists:estados_cumplimiento,id',
            'fecha_inicio'           => 'nullable|date',
            'fecha_fin'              => 'nullable|date|after_or_equal:fecha_inicio',
            'observaciones'          => 'nullable|string',
        ]);

        // Obtener la medida dentro del establecimiento
        $medida = MedidaRestaurativa::where('establecimiento_id', $establecimientoId)
            ->findOrFail($id);

        // Actualizar
        $medida->update([
            'tipo_medida_id'         => $validated['tipo_medida_id'],
            'responsable_funcionario'=> $validated['responsable_id'],
            'cumplimiento_estado_id' => $validated['cumplimiento_estado_id'],
            'fecha_inicio'           => $validated['fecha_inicio'],
            'fecha_fin'              => $validated['fecha_fin'],
            'observaciones'          => $validated['observaciones'],
        ]);

        return redirect()
            ->route('convivencia.medidas.index') 
            ->with('success', 'Medida restaurativa actualizada correctamente.');
    }
}

