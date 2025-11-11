<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Funcionario;
use App\Models\BitacoraIncidente;
use App\Models\BitacoraIncidenteAlumno;
use App\Models\EstadoIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\DocumentoAdjunto;

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

        return view('modulos.bitacora.index', compact('incidentes', 'cursos', 'estados', 'alumnos'));
    }

    /**
     * FORMULARIO CREATE
     */
    public function create()
    {
        $establecimientoId = session('establecimiento_id');

        return view('modulos.bitacora.create', [
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

            // Crear seguimiento emocional
            $seguimiento = SeguimientoEmocional::create([
                'alumno_id'          => $primerAlumno->id,
                'fecha'              => $request->fecha,
                'comentario'         => 'Seguimiento generado automáticamente.',
                'evaluado_por'       => $request->reportado_por,
                'establecimiento_id' => $establecimientoId,
            ]);

            // Asociarlo al incidente
            $incidente->update([
                'seguimiento_id' => $seguimiento->id,
            ]);

            // Adjuntar documentos
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $path = $archivo->store("incidentes/{$incidente->id}", 'public');

                    DocumentoAdjunto::create([
                        'entidad'            => 'bitacora_incidentes',
                        'entidad_id'         => $incidente->id,
                        'establecimiento_id' => $establecimientoId,
                        'nombre_archivo'     => $archivo->getClientOriginalName(),
                        'ruta_archivo'       => $path,
                        'subido_por'         => Auth::user()->funcionario_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('bitacora.index')
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
        $incidente = BitacoraIncidente::with([
            'estado',
            'reportadoPor',
            'involucrados.alumno.curso',
            'victimas',
            'agresores',
            'testigos',
            'documentos'
        ])->findOrFail($id);

        return view('modulos.bitacora.show', compact('incidente'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $establecimientoId = session('establecimiento_id');

        $incidente = BitacoraIncidente::with([
            'involucrados.alumno.curso',
            'reportadoPor',
            'documentos'
        ])
        ->delColegio($establecimientoId)
        ->findOrFail($id);

        return view('modulos.bitacora.edit', compact('incidente'));
    }

    /**
     * UPDATE COMPLETO
     */
    public function update(Request $request, $id)
    {
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

            // Primer alumno determina curso
            $primerAlumno = Alumno::findOrFail($request->alumnos[0]);

            // Actualizar datos base
            $incidente->update([
                'fecha'              => $request->fecha,
                'tipo_incidente'     => $request->tipo_incidente,
                'descripcion'        => $request->descripcion,
                'curso_id'           => $primerAlumno->curso_id,
                'reportado_por'      => $request->reportado_por,
                'estado_id'          => $request->estado_id,
            ]);

            // Eliminar involucrados anteriores
            BitacoraIncidenteAlumno::where('incidente_id', $incidente->id)->delete();

            // Registrar nuevos involucrados
            foreach ($request->alumnos as $index => $alumnoId) {

            // Si viene vacío o null → ignorar
            if (!$alumnoId) {
                continue;
            }

            $alumno = Alumno::find($alumnoId);

            // Si no existe el alumno → ignorar
            if (!$alumno) {
                continue;
            }

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
                        'entidad'            => 'bitacora_incidentes',
                        'entidad_id'         => $incidente->id,
                        'establecimiento_id' => $establecimientoId,
                        'nombre_archivo'     => $archivo->getClientOriginalName(),
                        'ruta_archivo'       => $path,
                        'subido_por'         => Auth::user()->funcionario_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('bitacora.index')
                ->with('success', 'Incidente actualizado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', 'Ocurrió un error al actualizar el incidente.')
                ->withInput();
        }
    }

    /**
     * ANULAR INCIDENTE
     */
    public function anular($id)
    {
        $incidente = BitacoraIncidente::findOrFail($id);

        $incidente->update([
            'estado_id' => EstadoIncidente::where('nombre', 'Anulado')->first()->id,
        ]);

        // Registrar en auditoría
        $incidente->auditoria()->create([
            'accion' => 'Anulado por error',
            'realizado_por' => auth()->id(),
        ]);

        return back()->with('success', 'Incidente anulado correctamente.');
    }
}
