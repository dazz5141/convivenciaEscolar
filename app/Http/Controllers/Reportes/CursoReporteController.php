<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Modelos
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\BitacoraIncidenteAlumno;
use App\Models\BitacoraIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\Derivacion;
use App\Models\NovedadInspectoria;
use App\Models\AsistenciaEvento;
use App\Models\RetiroAnticipado;
use App\Models\AccidenteEscolar;
use App\Models\CitacionApoderado;

class CursoReporteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = $user->rol_id;
        $establecimientoId = $user->establecimiento_id;

        // ===========================
        //   LISTA DE CURSOS SEGÚN ROL
        // ===========================
        $cursos = Curso::when($rol != 1, function($q) use ($establecimientoId) {
                return $q->where('establecimiento_id', $establecimientoId);
            })
            ->orderBy('nivel')
            ->orderBy('letra')
            ->get();


        // Si aún no se ha seleccionado curso → no mostrar datos
        if (!$request->curso_id) {
            return view('reportes.curso', [
                'cursos' => $cursos,
                'cursoSeleccionado' => null
            ]);
        }

        $cursoId = $request->curso_id;
        $cursoSeleccionado = Curso::findOrFail($cursoId);

        // ===========================
        //      KPIs POR CURSO
        // ===========================

        // Incidentes → desde pivot
        $incidentes = BitacoraIncidenteAlumno::where('curso_id', $cursoId)
            ->with('incidente')
            ->count();

        // Seguimientos emocionales
        $seguimientos = SeguimientoEmocional::whereHas('alumno', fn($q) =>
            $q->where('curso_id', $cursoId)
        )->count();

        // Derivaciones
        $derivaciones = Derivacion::whereHas('alumno', fn($q) =>
            $q->where('curso_id', $cursoId)
        )->count();

        // Accidentes
        $accidentes = AccidenteEscolar::whereHas('alumno', function ($q) use ($cursoId) {
            $q->where('curso_id', $cursoId);
        })->count();

        // Citaciones
        $citaciones = CitacionApoderado::whereHas('alumno', fn($q) =>
            $q->where('curso_id', $cursoId)
        )->count();

        // Novedades inspectoría
        $novedades = NovedadInspectoria::where('curso_id', $cursoId)->count();

        // Atrasos / Asistencia
        $atrasos = AsistenciaEvento::whereHas('alumno', fn($q) =>
            $q->where('curso_id', $cursoId)
        )->count();

        // Retiros anticipados
        $retiros = RetiroAnticipado::whereHas('alumno', fn($q) =>
            $q->where('curso_id', $cursoId)
        )->count();

        // ===========================
        //    GRÁFICO: INCIDENTES POR TIPO
        // ===========================
        $incidentesPorTipo = BitacoraIncidenteAlumno::where('bitacora_incidente_alumno.curso_id', $cursoId)
            ->join('bitacora_incidentes', 'bitacora_incidentes.id', '=', 'bitacora_incidente_alumno.incidente_id')
            ->selectRaw('bitacora_incidentes.tipo_incidente as tipo_incidente, COUNT(*) as total')
            ->groupBy('bitacora_incidentes.tipo_incidente')
            ->get();

        // ===========================
        //    LISTA ALUMNOS DEL CURSO
        // ===========================
        $alumnos = Alumno::where('curso_id', $cursoId)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->get();

        return view('reportes.curso', compact(
            'cursos',
            'cursoSeleccionado',
            'incidentes',
            'seguimientos',
            'derivaciones',
            'accidentes',
            'citaciones',
            'novedades',
            'atrasos',
            'retiros',
            'incidentesPorTipo',
            'alumnos'
        ));
    }
}
