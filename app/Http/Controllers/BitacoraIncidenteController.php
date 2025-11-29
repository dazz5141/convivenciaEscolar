<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Funcionario;
use App\Models\Usuario;
use App\Models\BitacoraIncidente;
use App\Models\BitacoraIncidenteAlumno;
use App\Models\EstadoIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\DocumentoAdjunto;
use App\Models\Notificacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BitacoraIncidenteController extends Controller
{
    /**
     * LISTADO + FILTROS
     */
    public function index(Request $request)
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'view')) abort(403);

        $establecimientoId = session('establecimiento_id');

        $query = BitacoraIncidente::with(['involucrados.alumno.curso', 'estado'])
            ->delColegio($establecimientoId)
            ->orderBy('fecha', 'desc');

        // Filtro por tipo
        if ($request->tipo) {
            $query->where('tipo_incidente', 'LIKE', "%{$request->tipo}%");
        }

        // Filtro por estado
        if ($request->estado_id) {
            $query->where('estado_id', $request->estado_id);
        }

        // Filtro por alumno
        if ($request->alumno_id) {
            $query->whereHas('involucrados', function ($q) use ($request) {
                $q->where('alumno_id', $request->alumno_id);
            });
        }

        // Filtro por curso
        if ($request->curso_id) {
            $query->where('curso_id', $request->curso_id);
        }

        $incidentes = $query->paginate(20);

        // Datos auxiliares para filtros
        $cursos = Curso::where('establecimiento_id', $establecimientoId)->get();
        $estados = EstadoIncidente::orderBy('nombre')->get();
        $alumnos = Alumno::where('activo', 1)
            ->whereHas('curso', fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->orderBy('apellido_paterno')
            ->get();

        return view('modulos.convivencia-escolar.bitacora.index', compact('incidentes', 'cursos', 'estados', 'alumnos'));
    }

    /**
     * FORMULARIO CREATE
     */
    public function create()
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'create')) abort(403);

        $establecimientoId = session('establecimiento_id');

        return view('modulos.convivencia-escolar.bitacora.create', [
            'alumnos' => Alumno::where('activo', 1)
                ->whereHas('curso', fn($q) => $q->where('establecimiento_id', $establecimientoId))
                ->orderBy('apellido_paterno')
                ->get(),

            'funcionarios' => Funcionario::activos()
                ->where('establecimiento_id', $establecimientoId)
                ->orderBy('apellido_paterno')
                ->get(),

            'estados' => EstadoIncidente::orderBy('nombre')->get(),
        ]);
    }

    /**
     * STORE COMPLETO
     */
    public function store(Request $request)
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'create')) abort(403);

        $request->validate([
            'fecha'          => 'required|date',
            'tipo_incidente' => 'required|string|max:120',
            'descripcion'    => 'required|string',

            'alumnos'        => 'required|array|min:1',
            'alumnos.*'      => 'exists:alumnos,id',

            'rol'            => 'required|array',
            'rol.*'          => 'in:victima,agresor,testigo',

            'reportado_por'  => 'required|exists:funcionarios,id',
            'estado_id'      => 'required|exists:estados_incidente,id',
        ]);

        // Debe haber el mismo número de alumnos y roles
        if (count($request->alumnos) !== count($request->rol)) {
            return back()
                ->with('error', 'Debe asignar un rol a cada alumno.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $establecimientoId = session('establecimiento_id');

            // Primer alumno determina el curso del incidente
            $primerAlumno = Alumno::findOrFail($request->alumnos[0]);

            // Crear incidente
            $incidente = BitacoraIncidente::create([
                'fecha'              => $request->fecha,
                'tipo_incidente'     => $request->tipo_incidente,
                'descripcion'        => $request->descripcion,
                'curso_id'           => $primerAlumno->curso_id,
                'reportado_por'      => $request->reportado_por,
                'estado_id'          => $request->estado_id,
                'establecimiento_id' => $establecimientoId,
            ]);

            // Registrar involucrados
            foreach ($request->alumnos as $index => $alumnoId) {
                BitacoraIncidenteAlumno::create([
                    'incidente_id'       => $incidente->id,
                    'alumno_id'          => $alumnoId,
                    'rol'                => $request->rol[$index],
                    'curso_id'           => Alumno::find($alumnoId)->curso_id,
                ]);
            }

            // Adjuntar documentos
            if ($request->hasFile('archivos')) {

                foreach ($request->file('archivos') as $archivo) {

                    // Guardado real del archivo
                    $ruta = $archivo->store("documentos/bitacora/{$incidente->id}", 'public');

                    DocumentoAdjunto::create([
                        'entidad_type'       => BitacoraIncidente::class,
                        'entidad_id'         => $incidente->id,
                        'establecimiento_id' => $establecimientoId,
                        'nombre_archivo'     => $archivo->getClientOriginalName(),
                        'ruta_archivo'       => "storage/" . $ruta,
                        'subido_por'         => Auth::user()->funcionario_id,
                    ]);
                }
            }

            /*
            |------------------------------------------------------------------
            | NOTIFICACIONES AUTOMÁTICAS – BITÁCORA INCIDENTE
            |------------------------------------------------------------------
            */

            $rolesDestino = [3, 8]; 

            $usuariosDestino = Usuario::whereIn('rol_id', $rolesDestino)
                ->where('establecimiento_id', $establecimientoId)
                ->where('activo', 1)
                ->get();

            foreach ($usuariosDestino as $u) {
                Notificacion::create([
                    'usuario_id'        => $u->id,
                    'origen_id'         => $incidente->id,
                    'origen_model'      => BitacoraIncidente::class,
                    'tipo'              => 'incidente',
                    'mensaje'           => "Nuevo incidente reportado para {$primerAlumno->nombre_completo}.",
                    'establecimiento_id'=> $establecimientoId,
                ]);
            }

            DB::commit();

            /* ===========================================
            AUDITORÍA → CREACIÓN DE INCIDENTE
            =========================================== */
            logAuditoria(
                accion: 'create',
                modulo: 'bitacora',
                detalle: 'Se creó el incidente ID ' . $incidente->id,
                establecimiento_id: $incidente->establecimiento_id
            );

            return redirect()
                ->route('convivencia.bitacora.index')
                ->with('success', 'Incidente registrado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', 'Ocurrió un error al registrar el incidente.')
                ->withInput();
        }
    }

    /**
     * SHOW DETALLADO
     */
    public function show($id)
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'view')) abort(403);

        $incidente = BitacoraIncidente::with([
            'estado',
            'reportadoPor',
            'involucrados.alumno.curso',
            'victimas',
            'agresores',
            'testigos',
            'documentos',
            'observaciones.funcionario'
        ])->findOrFail($id);

        return view('modulos.convivencia-escolar.bitacora.show', compact('incidente'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'edit')) abort(403);

        $establecimientoId = session('establecimiento_id');

        $incidente = BitacoraIncidente::with([
            'involucrados.alumno.curso',
            'reportadoPor',
            'documentos'
        ])
        ->delColegio($establecimientoId)
        ->findOrFail($id);

        return view('modulos.convivencia-escolar.bitacora.edit', compact('incidente'));
    }

    /**
     * UPDATE COMPLETO
     */
    public function update(Request $request, $id)
    {
        // -------- PERMISO --------
        if (!canAccess('bitacora', 'edit')) abort(403);

        $request->validate([
            'fecha'          => 'required|date',
            'tipo_incidente' => 'required|string|max:120',
            'descripcion'    => 'required|string',

            'alumnos'        => 'required|array|min:1',
            'alumnos.*'      => 'exists:alumnos,id',

            'rol'            => 'required|array',
            'rol.*'          => 'in:victima,agresor,testigo',

            'reportado_por'  => 'required|exists:funcionarios,id',
            'estado_id'      => 'required|exists:estados_incidente,id',
        ]);

        if (count($request->alumnos) !== count($request->rol)) {
            return back()
                ->with('error', 'Debe asignar un rol a cada alumno.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $establecimientoId = session('establecimiento_id');

            $incidente = BitacoraIncidente::findOrFail($id);

            $primerAlumno = Alumno::findOrFail($request->alumnos[0]);

            // Actualizar datos base
            $incidente->update([
                'fecha'          => $request->fecha,
                'tipo_incidente' => $request->tipo_incidente,
                'descripcion'    => $request->descripcion,
                'curso_id'       => $primerAlumno->curso_id,
                'reportado_por'  => $request->reportado_por,
                'estado_id'      => $request->estado_id,
            ]);

            // Eliminar involucrados anteriores
            BitacoraIncidenteAlumno::where('incidente_id', $incidente->id)->delete();

            // Registrar nuevos involucrados
            foreach ($request->alumnos as $index => $alumnoId) {

                if (!$alumnoId) continue;

                $alumno = Alumno::find($alumnoId);

                if (!$alumno) continue;

                BitacoraIncidenteAlumno::create([
                    'incidente_id' => $incidente->id,
                    'alumno_id'    => $alumnoId,
                    'rol'          => $request->rol[$index],
                    'curso_id'     => $alumno->curso_id,
                ]);
            }

            // Adjuntar nuevos documentos
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $path = $archivo->store("incidentes/{$incidente->id}", 'public');

                    DocumentoAdjunto::create([
                        'entidad_type'       => BitacoraIncidente::class,
                        'entidad_id'         => $incidente->id,
                        'establecimiento_id' => $establecimientoId,
                        'nombre_archivo'     => $archivo->getClientOriginalName(),
                        'ruta_archivo'       => "storage/" . $path,
                        'subido_por'         => Auth::user()->funcionario_id,
                    ]);
                }
            }

            DB::commit();

            /* ===========================================
            AUDITORÍA → ACTUALIZACIÓN DE INCIDENTE
            =========================================== */
            logAuditoria(
                accion: 'update',
                modulo: 'bitacora',
                detalle: 'Se actualizó el incidente ID ' . $incidente->id,
                establecimiento_id: $incidente->establecimiento_id
            );

            return redirect()
                ->route('convivencia.bitacora.index')
                ->with('success', 'Incidente actualizado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', 'Ocurrió un error al actualizar el incidente.')
                ->withInput();
        }
    }
}
