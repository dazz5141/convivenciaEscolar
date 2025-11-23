<?php

namespace App\Http\Controllers;

use App\Models\SeguimientoEmocional;
use App\Models\EstadoSeguimientoEmocional;
use App\Models\NivelEmocional;
use App\Models\Alumno;
use App\Models\Funcionario;
use App\Models\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoEmocionalController extends Controller
{
    /**
     * LISTADO + FILTROS
     */
    public function index(Request $request)
    {
        $establecimientoId = session('establecimiento_id');

        $query = SeguimientoEmocional::with(['alumno.curso', 'evaluador', 'nivel'])
            ->delColegio($establecimientoId)
            ->orderBy('fecha', 'desc');

        // Filtro alumno
        if ($request->alumno_id) {
            $query->where('alumno_id', $request->alumno_id);
        }

        // Filtro estado
        if ($request->estado_id) {
            $query->where('estado_id', $request->estado_id);
        }

        // Filtro nivel
        if ($request->nivel_emocional_id) {
            $query->where('nivel_emocional_id', $request->nivel_emocional_id);
        }

        // Filtro fecha
        if ($request->fecha) {
            $query->where('fecha', $request->fecha);
        }

        $seguimientos = $query->paginate(20);

        return view('modulos.convivencia-escolar.seguimiento-emocional.index', [
            'seguimientos' => $seguimientos,
            'alumnos'      => Alumno::where('activo', 1)
                ->whereHas('curso', fn($q) => $q->where('establecimiento_id', $establecimientoId))
                ->orderBy('apellido_paterno')->get(),

            'estados' => EstadoSeguimientoEmocional::orderBy('nombre')->get(),
            'niveles' => NivelEmocional::orderBy('nombre')->get(),
        ]);
    }


    /**
     * FORM CREATE
     */
    public function create()
    {
        $establecimientoId = session('establecimiento_id');

        return view('modulos.convivencia-escolar.seguimiento-emocional.create', [
            'alumnos' => Alumno::where('activo', 1)
                ->whereHas('curso', fn($q) => $q->where('establecimiento_id', $establecimientoId))
                ->orderBy('apellido_paterno')->get(),

            'funcionarios' => Funcionario::where('activo', 1)
                ->where('establecimiento_id', $establecimientoId)
                ->orderBy('apellido_paterno')->get(),

            'niveles' => NivelEmocional::orderBy('nombre')->get(),
            'estados' => EstadoSeguimientoEmocional::orderBy('nombre')->get(),
        ]);
    }


    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'         => 'required|exists:alumnos,id',
            'fecha'             => 'required|date',
            'nivel_emocional_id'=> 'nullable|exists:niveles_emocionales,id',
            'estado_id'         => 'required|exists:estados_seguimiento_emocional,id',
            'comentario'        => 'nullable|string',
            'evaluado_por'      => 'required|exists:funcionarios,id',
        ]);

        $seguimiento = SeguimientoEmocional::create([
            'alumno_id'          => $request->alumno_id,
            'fecha'              => $request->fecha,
            'nivel_emocional_id' => $request->nivel_emocional_id,
            'estado_id'          => $request->estado_id,
            'comentario'         => $request->comentario,
            'evaluado_por'       => $request->evaluado_por,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES AUTOMÁTICAS
        |--------------------------------------------------------------------------
        */

        $establecimientoId = session('establecimiento_id');

        // Roles que recibirán la notificación
        $rolesDestino = [6, 7]; // Convivencia Escolar + Psicólogo

        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        // Obtener alumno 
        $alumno = Alumno::select('nombre', 'apellido_paterno', 'apellido_materno')->find($request->alumno_id);
        $nombreAlumno = $alumno ? "{$alumno->nombre} {$alumno->apellido_paterno}" : 'Alumno';

        if ($usuariosDestino->count() > 0) {
            foreach ($usuariosDestino as $usuario) {
                Notificacion::create([
                    'usuario_id'         => $usuario->id,
                    'origen_id'          => $seguimiento->id,
                    'origen_model'       => SeguimientoEmocional::class,
                    'tipo'               => 'seguimiento_emocional',
                    'mensaje'            => "Nuevo seguimiento emocional registrado para {$nombreAlumno}.",
                    'establecimiento_id' => $establecimientoId,
                ]);
            }
        }

        return redirect()
            ->route('convivencia.seguimiento.index')
            ->with('success', 'Seguimiento emocional registrado correctamente.');
    }


    /**
     * SHOW
     */
    public function show($id)
    {
        $seguimiento = SeguimientoEmocional::with([
            'alumno.curso',
            'nivel',
            'estado',
            'evaluador.cargo',
            'observaciones.funcionario'
        ])->findOrFail($id);

        return view('modulos.convivencia-escolar.seguimiento-emocional.show', compact('seguimiento'));
    }


    /**
     * EDIT
     */
    public function edit($id)
    {
        $seguimiento = SeguimientoEmocional::findOrFail($id);

        $establecimientoId = session('establecimiento_id');

        return view('modulos.convivencia-escolar.seguimiento-emocional.edit', [
            'seguimiento' => $seguimiento,
            'alumnos' => Alumno::where('activo', 1)
                ->whereHas('curso', fn($q) => $q->where('establecimiento_id', $establecimientoId))
                ->orderBy('apellido_paterno')->get(),

            'funcionarios' => Funcionario::where('activo', 1)
                ->where('establecimiento_id', $establecimientoId)
                ->orderBy('apellido_paterno')->get(),

            'niveles' => NivelEmocional::orderBy('nombre')->get(),
            'estados' => EstadoSeguimientoEmocional::orderBy('nombre')->get(),
        ]);
    }


    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $seguimiento = SeguimientoEmocional::findOrFail($id);

        $request->validate([
            'alumno_id'         => 'required|exists:alumnos,id',
            'fecha'             => 'required|date',
            'nivel_emocional_id'=> 'nullable|exists:niveles_emocionales,id',
            'estado_id'         => 'required|exists:estados_seguimiento_emocional,id',
            'comentario'        => 'nullable|string',
            'evaluado_por'      => 'required|exists:funcionarios,id',
        ]);

        $seguimiento->update([
            'alumno_id'        => $request->alumno_id,
            'fecha'            => $request->fecha,
            'nivel_emocional_id' => $request->nivel_emocional_id,
            'estado_id'        => $request->estado_id,
            'comentario'       => $request->comentario,
            'evaluado_por'     => $request->evaluado_por,
        ]);

        return redirect()
            ->route('convivencia.seguimiento.index')
            ->with('success', 'Seguimiento emocional actualizado correctamente.');
    }
}
