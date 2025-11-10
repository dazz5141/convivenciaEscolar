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

        $alumno = Alumno::create($request->except('apoderados'));

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
        $alumno = Alumno::with(['curso', 'region', 'provincia', 'comuna', 'apoderados'])
            ->findOrFail($id);

        return view('modulos.alumnos.show', compact('alumno'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
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
        $alumno = Alumno::findOrFail($id);

        $request->validate([
            'run' => 'required|max:20|unique:alumnos,run,' . $alumno->id,
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'curso_id' => 'required|exists:cursos,id',
            'email' => 'nullable|email|unique:alumnos,email,' . $alumno->id,
        ]);

        // Actualizar datos
        $alumno->update($request->except('apoderados'));

        // Actualizar apoderado único (por el momento)
        $alumno->apoderados()->detach();

        if ($request->apoderado_id) {
            $alumno->apoderados()->attach($request->apoderado_id, ['tipo' => 'titular']);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * DESHABILITAR
     */
    public function disable($id)
    {
        Alumno::findOrFail($id)->update(['activo' => 0]);

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno deshabilitado.');
    }

    /**
     * HABILITAR
     */
    public function enable($id)
    {
        Alumno::findOrFail($id)->update(['activo' => 1]);

        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno habilitado.');
    }
}
