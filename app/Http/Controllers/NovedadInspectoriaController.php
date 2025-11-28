<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NovedadInspectoria;
use App\Models\TipoNovedadInspectoria;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class NovedadInspectoriaController extends Controller
{
    /**
     * Listado general de novedades
     */
    public function index()
    {
        // Permiso
        if (!canAccess('novedades', 'view')) {
            abort(403, 'No tienes permiso para ver novedades.');
        }

        $establecimiento = session('establecimiento_id');

        $novedades = NovedadInspectoria::with([
                'funcionario',
                'alumno.curso',
                'curso',
                'tipo'
            ])
            ->delColegio($establecimiento)
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        // FILTRO DE TIPOS
        $tipos = TipoNovedadInspectoria::orderBy('nombre')->get();

        return view('modulos.inspectoria.novedades.index', compact('novedades', 'tipos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        // Permiso
        if (!canAccess('novedades', 'create')) {
            abort(403, 'No tienes permiso para registrar novedades.');
        }

        $tipos = TipoNovedadInspectoria::orderBy('nombre')->get();

        return view('modulos.inspectoria.novedades.create', compact('tipos'));
    }

    /**
     * Guardar novedad
     */
    public function store(Request $request)
    {
        // Permiso
        if (!canAccess('novedades', 'create')) {
            abort(403, 'No tienes permiso para registrar novedades.');
        }

        if (!$request->alumno_id) {
            $request->merge(['curso_id' => null]);
        }

        $request->validate([
            'fecha'               => 'required|date',
            'tipo_novedad_id'     => 'required|exists:tipos_novedad_inspectoria,id',
            'descripcion'         => 'required|string',
            'alumno_id'           => 'nullable|exists:alumnos,id',
            'curso_id'            => 'nullable|exists:cursos,id',
        ]);

        // Crear novedad
        $n = NovedadInspectoria::create([
            'fecha'              => $request->fecha,
            'tipo_novedad_id'    => $request->tipo_novedad_id,
            'descripcion'        => $request->descripcion,
            'alumno_id'          => $request->alumno_id,
            'curso_id'           => $request->curso_id,
            'funcionario_id'     => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICACIONES AUTOMÁTICAS
        |--------------------------------------------------------------------------
        */

        $establecimientoId = session('establecimiento_id');

        // Roles que recibirán la notificación
        $rolesDestino = [4, 5, 6]; // Inspector General, Inspector, Convivencia Escolar

        // Usuarios destino
        $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
            ->where('establecimiento_id', $establecimientoId)
            ->where('activo', 1)
            ->get();

        if ($usuariosDestino->count() > 0) {

            $alumnoNombre = $request->alumno_id
                ? Alumno::find($request->alumno_id)->nombre_completo
                : 'Sin alumno asociado';

            foreach ($usuariosDestino as $usuario) {

                Notificacion::create([
                    'usuario_id'        => $usuario->id,
                    'origen_id'         => $n->id,
                    'origen_model'      => NovedadInspectoria::class,
                    'tipo'              => 'novedad_inspectoria',
                    'mensaje'           => "Nueva novedad registrada: {$alumnoNombre}",
                    'establecimiento_id'=> $establecimientoId,
                ]);
            }
        }

        return redirect()
            ->route('inspectoria.novedades.index')
            ->with('success', 'Novedad registrada correctamente.');
    }

    /**
     * Ver detalle
     */
    public function show(NovedadInspectoria $novedad)
    {
        // Permiso
        if (!canAccess('novedades', 'view')) {
            abort(403, 'No tienes permiso para ver novedades.');
        }

        $this->validarEstablecimiento($novedad);

        return view('modulos.inspectoria.novedades.show', compact('novedad'));
    }

    /**
     * Formulario editar
     */
    public function edit(NovedadInspectoria $novedad)
    {
        // Permiso
        if (!canAccess('novedades', 'edit')) {
            abort(403, 'No tienes permiso para editar novedades.');
        }

        $this->validarEstablecimiento($novedad);

        $tipos = TipoNovedadInspectoria::orderBy('nombre')->get();

        return view('modulos.inspectoria.novedades.edit', compact('novedad', 'tipos'));
    }

    /**
     * Actualizar novedad
     */
    public function update(Request $request, NovedadInspectoria $novedad)
    {
        // Permiso
        if (!canAccess('novedades', 'edit')) {
            abort(403, 'No tienes permiso para editar novedades.');
        }

        $this->validarEstablecimiento($novedad);

        if (!$request->alumno_id) {
            $request->merge(['curso_id' => null]);
        }

        $request->validate([
            'tipo_novedad_id'     => 'required|exists:tipos_novedad_inspectoria,id',
            'descripcion'         => 'required|string',
        ]);

        $novedad->update([
            'tipo_novedad_id'    => $request->tipo_novedad_id,
            'descripcion'        => $request->descripcion,
        ]);

        return redirect()
            ->route('inspectoria.novedades.index')
            ->with('success', 'Novedad actualizada correctamente.');
    }

    /**
     * Seguridad multicoegio
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
