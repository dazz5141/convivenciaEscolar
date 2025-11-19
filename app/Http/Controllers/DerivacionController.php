<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Derivacion;
use App\Models\BitacoraIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\MedidaRestaurativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DerivacionController extends Controller
{
    /**
     * LISTADO
     */
    public function index(Request $request)
    {
        $establecimiento = session('establecimiento_id');

        $query = Derivacion::with(['alumno', 'funcionario'])
            ->delColegio($establecimiento)
            ->orderBy('fecha', 'desc');

        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        $derivaciones = $query->paginate(15);

        $alumnos = Alumno::whereHas('curso', function ($q) use ($establecimiento) {
                $q->where('establecimiento_id', $establecimiento);
            })
            ->orderBy('apellido_paterno')
            ->get();

        return view('modulos.convivencia-escolar.derivaciones.index', compact(
            'derivaciones', 'alumnos'
        ));
    }


    /**
     * CREAR
     */
    public function create(Request $request)
    {
        $tipo = $request->tipo_entidad;   // seguimiento | medida
        $entidadId = $request->entidad_id; 

        $alumno = null;
        $tipoEntidad = null;

        // Si viene desde seguimiento emocional
        if ($tipo === 'seguimiento' && $entidadId) {
            $origen = SeguimientoEmocional::with('alumno')->find($entidadId);
            $alumno = $origen?->alumno;
            $tipoEntidad = 'seguimiento';
        }

        // Si viene desde medida restaurativa
        if ($tipo === 'medida' && $entidadId) {
            $origen = MedidaRestaurativa::with('alumno')->find($entidadId);
            $alumno = $origen?->alumno;
            $tipoEntidad = 'medida';
        }

        return view('modulos.convivencia-escolar.derivaciones.create', [
            'alumno'      => $alumno,
            'tipoEntidad' => $tipoEntidad,
            'entidadId'   => $entidadId,
        ]);
    }

    /**
     * GUARDAR
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'     => 'required|exists:alumnos,id',
            'tipo'          => 'required|string|max:50',
            'destino'       => 'required|string|max:120',
            'motivo'        => 'nullable|string',
            'estado'        => 'required|string|max:60',
            'fecha'         => 'required|date',

            'tipo_entidad'  => 'nullable|string',
            'entidad_id'    => 'nullable|integer',
        ]);

        // Convertir a clase
        $entidadType = null;

        if ($request->tipo_entidad === 'seguimiento') {
            $entidadType = SeguimientoEmocional::class;
        }

        if ($request->tipo_entidad === 'medida') {
            $entidadType = MedidaRestaurativa::class;
        }

        $derivacion = Derivacion::create([
            'alumno_id'          => $request->alumno_id,
            'entidad_type'       => $entidadType,
            'entidad_id'         => $request->entidad_id,
            'tipo'               => $request->tipo,
            'destino'            => $request->destino,
            'motivo'             => $request->motivo,
            'estado'             => $request->estado,
            'fecha'              => $request->fecha,
            'registrado_por'     => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()
            ->route('convivencia.derivaciones.index')
            ->with('success', 'Derivación registrada correctamente.');
    }

    /**
     * VER
     */
    public function show(Derivacion $derivacion)
    {
        if ($derivacion->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        return view('modulos.convivencia-escolar.derivaciones.show', compact('derivacion'));
    }


    /**
     * EDITAR
     */
    public function edit($id)
    {
        // Buscar derivación
        $derivacion = Derivacion::with([
            'alumno.curso',
            'funcionario',
            'entidad'
        ])->findOrFail($id);

        // Validar establecimiento
        if ($derivacion->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        return view('modulos.convivencia-escolar.derivaciones.edit', compact('derivacion'));
    }


    /**
     * ACTUALIZAR
     */
    public function update(Request $request, Derivacion $derivacion)
    {
        if ($derivacion->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $request->validate([
            'tipo'      => 'required|string|max:50',
            'destino'   => 'required|string|max:120',
            'motivo'    => 'nullable|string',
            'estado'    => 'required|string|max:60',
            'fecha'     => 'required|date',
        ]);

        $derivacion->update($request->all());

        return redirect()
            ->route('convivencia.derivaciones.show', $derivacion->id)
            ->with('success', 'Derivación actualizada correctamente.');
    }
}
