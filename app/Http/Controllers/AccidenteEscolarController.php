<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccidenteEscolar;
use App\Models\TipoAccidente;
use App\Models\Alumno;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class AccidenteEscolarController extends Controller
{
    /**
     * LISTADO GENERAL
     */
    public function index(Request $request)
    {
        $query = AccidenteEscolar::with(['alumno.curso', 'tipo', 'funcionario', 'usuario'])
            ->delColegio(session('establecimiento_id'));

        // FILTRO: Alumno seleccionado desde modal
        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
            $alumnoSeleccionado = \App\Models\Alumno::find($request->alumno_id);
        } else {
            $alumnoSeleccionado = null;
        }

        // FILTRO: Tipo accidente
        if ($request->filled('tipo')) {
            $query->where('tipo_accidente_id', $request->tipo);
        }

        // FILTRO: Fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $accidentes = $query->orderBy('fecha', 'desc')->paginate(15);
        $tipos = TipoAccidente::orderBy('nombre')->get();

        return view('modulos.inspectoria.accidentes.index', compact(
            'accidentes',
            'tipos',
            'alumnoSeleccionado'
        ));
    }

    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        $alumnos = Alumno::with('curso')
            ->whereHas('curso', function ($q) {
                $q->where('establecimiento_id', session('establecimiento_id'));
            })
            ->get()
            ->sortBy('nombre_completo'); // ← ORDEN CORRECTO

        $tipos = TipoAccidente::orderBy('nombre')->get();

        return view('modulos.inspectoria.accidentes.create', compact('alumnos', 'tipos'));
    }


    /**
     * GUARDAR ACCIDENTE
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha'             => 'required|date',
            'alumno_id'         => 'required|exists:alumnos,id',
            'tipo_accidente_id' => 'required|exists:tipos_accidente,id',
            'lugar'             => 'required|string|max:255',
            'descripcion'       => 'required|string',
            'atencion_inmediata'=> 'nullable|string',
            'derivacion_salud'  => 'nullable|string',
        ]);
        
        $establecimientoId = session('establecimiento_id');

        AccidenteEscolar::create([
            'fecha'              => $request->fecha,
            'alumno_id'          => $request->alumno_id,
            'tipo_accidente_id'  => $request->tipo_accidente_id,
            'lugar'              => $request->lugar,
            'descripcion'        => $request->descripcion,
            'atencion_inmediata' => $request->atencion_inmediata,
            'derivacion_salud'   => $request->derivacion_salud, // texto real
            'registrado_por' => Auth::user()->id,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES AUTOMÁTICAS – ACCIDENTES ESCOLARES
        |--------------------------------------------------------------------------
        */

        // Roles destino (ajustables)
        $rolesDestino = [3, 8]; 
        // 3 = Inspector General
        // 8 = Encargado de Convivencia Escolar

        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        foreach ($usuariosDestino as $u) {
            Notificacion::create([
                'usuario_id'        => $u->id,
                'origen_id'         => $accidente->id,
                'origen_model'      => AccidenteEscolar::class,
                'tipo'              => 'accidente',
                'mensaje'           => "Nuevo accidente escolar registrado para {$alumno->nombre_completo}.",
                'establecimiento_id'=> $establecimientoId,
            ]);
        }

        return redirect()
            ->route('inspectoria.accidentes.index')
            ->with('success', 'Accidente registrado correctamente.');
    }


    /**
     * MOSTRAR DETALLE
     */
    public function show(AccidenteEscolar $accidente)
    {
        $this->validarEstablecimiento($accidente);

        return view('modulos.inspectoria.accidentes.show', compact('accidente'));
    }


    /**
     * FORMULARIO DE EDICIÓN
     */
    public function edit(AccidenteEscolar $accidente)
    {
        $this->validarEstablecimiento($accidente);

        $tipos = TipoAccidente::orderBy('nombre')->get();

        return view('modulos.inspectoria.accidentes.edit', compact('accidente', 'tipos'));
    }


    /**
     * ACTUALIZAR
     */
    public function update(Request $request, AccidenteEscolar $accidente)
    {
        $this->validarEstablecimiento($accidente);

        $request->validate([
            'tipo_accidente_id'  => 'required|exists:tipos_accidente,id',
            'lugar'              => 'required|string|max:255',
            'descripcion'        => 'required|string',
            'atencion_inmediata' => 'nullable|string',
            'derivacion_salud'   => 'nullable|string',
        ]);

        $accidente->update([
            'tipo_accidente_id'  => $request->tipo_accidente_id,
            'lugar'              => $request->lugar,
            'descripcion'        => $request->descripcion,
            'atencion_inmediata' => $request->atencion_inmediata,
            'derivacion_salud'   => $request->derivacion_salud,
        ]);

        return redirect()
            ->route('inspectoria.accidentes.index')
            ->with('success', 'Accidente actualizado correctamente.');
    }


    /**
     * VALIDAR MULTICOLEGIO
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        // 1. Si el modelo NO tiene establecimiento_id
        if (!$establecimientoModelo) {

            // En desarrollo: mostrar mensaje útil en log y permitir continuar
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] El modelo ".get_class($modelo)." (ID: {$modelo->id}) no tiene establecimiento_id definido.");
                return;
            } 
            
            // En producción: cortar acceso
            abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
        }

        // 2. Si el registro pertenece a otro establecimiento
        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }
}
