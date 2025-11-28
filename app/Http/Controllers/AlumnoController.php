<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Curso;
use App\Models\Region;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * LISTADO + FILTROS
     */
    public function index(Request $request)
    {
        // 🔐 Permiso: ver alumnos
        if (!canAccess('alumnos', 'view')) {
            abort(403);
        }

        $establecimientoId = session('establecimiento_id');

        $query = Alumno::whereHas('curso', function ($q) use ($establecimientoId) {
            $q->where('establecimiento_id', $establecimientoId);
        });

        // Filtro búsqueda (run/nombre completo)
        if ($request->buscar) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('run', 'LIKE', "%$buscar%")
                  ->orWhereRaw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) LIKE ?", ["%$buscar%"]);
            });
        }

        // Filtro curso
        if ($request->curso_id) {
            $query->where('curso_id', $request->curso_id);
        }

        // Filtro estado
        if ($request->estado !== null && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        $alumnos = $query->orderBy('apellido_paterno')->paginate(20);

        $cursos = Curso::where('establecimiento_id', $establecimientoId)
               ->orderBy('nivel')
               ->orderBy('letra')
               ->get();

        return view('modulos.alumnos.index', compact('alumnos', 'cursos'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        // 🔐 Permiso: crear alumnos
        if (!canAccess('alumnos', 'create')) {
            abort(403);
        }

        $establecimientoId = session('establecimiento_id');

        $regiones = Region::orderBy('nombre')->get();
        $cursos = Curso::where('establecimiento_id', $establecimientoId)
               ->orderBy('nivel')
               ->orderBy('letra')
               ->get();
        $apoderados = Apoderado::where('establecimiento_id', $establecimientoId)->orderBy('apellido_paterno')->get();

        return view('modulos.alumnos.create', compact('cursos', 'regiones', 'apoderados'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        // 🔐 Permiso: crear alumnos
        if (!canAccess('alumnos', 'create')) {
            abort(403);
        }

        $request->validate([
            'run' => 'required|unique:alumnos,run|max:20',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'curso_id' => 'required|exists:cursos,id',
            'sexo_id' => 'nullable|exists:sexos,id',

            'email' => 'nullable|email|unique:alumnos,email',

            'region_id' => 'nullable|exists:regiones,id',
            'provincia_id' => 'nullable|exists:provincias,id',
            'comuna_id' => 'nullable|exists:comunas,id',
        ]);

        // Crear alumno SIN apoderados todavía
        $alumno = Alumno::create($request->except('apoderados'));

        // Refrescar relaciones
        $alumno->refresh();

        // Registrar matrícula inicial en historial
        $alumno->historialCursos()->create([
            'curso_id' => $alumno->curso_id,
            'establecimiento_id' => $alumno->curso->establecimiento_id,
            'fecha_cambio' => $alumno->fecha_ingreso ?? now(),
            'motivo' => 'Matrícula inicial'
        ]);

        // Vincular apoderado único (por el momento)
        if ($request->apoderado_id) {
            $alumno->apoderados()->attach($request->apoderado_id, ['tipo' => 'titular']);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno registrado correctamente.');
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        // 🔐 Permiso: ver
        if (!canAccess('alumnos', 'view')) {
            abort(403);
        }

        $alumno = Alumno::with(['curso', 'region', 'provincia', 'comuna', 'apoderados', 'historialCursos.curso'])
            ->findOrFail($id);

        return view('modulos.alumnos.show', compact('alumno'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        // 🔐 Permiso: editar alumnos
        if (!canAccess('alumnos', 'edit')) {
            abort(403);
        }

        $establecimientoId = session('establecimiento_id');

        $alumno = Alumno::findOrFail($id);

        $regiones = Region::orderBy('nombre')->get();
        $cursos = Curso::where('establecimiento_id', $establecimientoId)
               ->orderBy('nivel')
               ->orderBy('letra')
               ->get();
        $apoderados = Apoderado::where('establecimiento_id', $establecimientoId)->orderBy('apellido_paterno')->get();

        return view('modulos.alumnos.edit', compact('alumno', 'regiones', 'cursos', 'apoderados'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        // 🔐 Permiso: editar alumnos
        if (!canAccess('alumnos', 'edit')) {
            abort(403);
        }

        $alumno = Alumno::findOrFail($id);

        // Guardar curso anterior
        $cursoAnterior = $alumno->curso_id;

        // Validación
        $request->validate([
            'run' => 'required|max:20|unique:alumnos,run,' . $alumno->id,
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'curso_id' => 'required|exists:cursos,id',
            'email' => 'nullable|email|unique:alumnos,email,' . $alumno->id,
        ]);

        // Actualizar el alumno (excepto apoderados)
        $alumno->update($request->except('apoderados'));

        // ACTUALIZAR APODERADO
        $alumno->apoderados()->detach();

        if ($request->apoderado_id) {
            $alumno->apoderados()->attach($request->apoderado_id, ['tipo' => 'titular']);
        }

        // REFRESCAR para obtener el curso NUEVO
        $alumno->refresh();

        // Registrar historial SOLO si realmente cambió el curso
        if ($cursoAnterior != $request->curso_id) {
            $alumno->historialCursos()->create([
                'curso_id' => $request->curso_id,
                'establecimiento_id' => $alumno->curso->establecimiento_id,
                'fecha_cambio' => now(),
                'motivo' => 'Cambio de curso desde edición'
            ]);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * DESHABILITAR
     */
    public function disable($id)
    {
        // 🔐 Permiso: desactivar alumnos
        if (!canAccess('alumnos', 'disable')) {
            abort(403);
        }

        Alumno::findOrFail($id)->update(['activo' => 0]);

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        // 🔐 Permiso: desactivar (o habilitar, usa mismo tipo)
        if (!canAccess('alumnos', 'disable')) {
            abort(403);
        }

        Alumno::findOrFail($id)->update(['activo' => 1]);

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno habilitado.');
    }

    /**
     * FORM CAMBIAR CURSO
     */
    public function cambiarCursoForm($id)
    {
        // 🔐 Permiso: editar alumnos
        if (!canAccess('alumnos', 'edit')) {
            abort(403);
        }

        $alumno = Alumno::findOrFail($id);

        // solo cursos del mismo establecimiento
        $cursos = Curso::where('establecimiento_id', $alumno->curso->establecimiento_id)
                    ->where('anio', $alumno->curso->anio) 
                    ->orderBy('nivel')
                    ->orderBy('letra')
                    ->get();

        return view('modulos.alumnos.cambiar-curso', compact('alumno', 'cursos'));
    }

    public function cambiarCurso(Request $request, $id)
    {
        // 🔐 Permiso: editar alumnos
        if (!canAccess('alumnos', 'edit')) {
            abort(403);
        }

        $alumno = Alumno::findOrFail($id);

        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'motivo' => 'nullable|string|max:500'
        ]);

        $cursoAnterior = $alumno->curso_id;

        // Actualizar curso
        $alumno->curso_id = $request->curso_id;
        $alumno->save();

        // Registrar cambio en historial
        $alumno->historialCursos()->create([
            'curso_id' => $request->curso_id,
            'establecimiento_id' => $alumno->curso->establecimiento_id,
            'fecha_cambio' => now(),
            'motivo' => $request->motivo ?? 'Cambio de curso'
        ]);

        return redirect()->route('alumnos.show', $alumno->id)
            ->with('success', 'Curso actualizado correctamente.');
    }
}
