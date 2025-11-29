<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Modelos
use App\Models\Alumno;
use App\Models\BitacoraIncidenteAlumno;
use App\Models\SeguimientoEmocional;
use App\Models\Derivacion;
use App\Models\NovedadInspectoria;
use App\Models\AsistenciaEvento;
use App\Models\RetiroAnticipado;
use App\Models\AccidenteEscolar;
use App\Models\CitacionApoderado;

class AlumnoReporteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $establecimientoId = $user->establecimiento_id;

        // ======================================================
        //   PERMISO: VER REPORTE POR ALUMNO
        // ======================================================
        if (!canAccess('reporte_alumno', 'view')) {
            abort(403, 'No tienes permisos para ver el reporte por alumno.');
        }

        // ======================================================
        //   LISTA DE ALUMNOS DEL ESTABLECIMIENTO
        // ======================================================
        $alumnos = Alumno::whereHas('curso', function ($q) use ($establecimientoId) {
                $q->where('establecimiento_id', $establecimientoId);
            })
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        // SI NO HAY ALUMNO SELECCIONADO -> SOLO MOSTRAR FORMULARIO
        if (!$request->alumno_id) {
            return view('reportes.alumno', [
                'alumnos' => $alumnos,
                'alumnoSeleccionado' => null
            ]);
        }

        $alumnoId = $request->alumno_id;
        $alumnoSeleccionado = Alumno::findOrFail($alumnoId);

        // ======================================================
        //   KPIs DEL ALUMNO
        // ======================================================

        // Incidentes (pivot)
        $incidentes = BitacoraIncidenteAlumno::where('alumno_id', $alumnoId)->count();

        // Seguimientos emocionales
        $seguimientos = SeguimientoEmocional::where('alumno_id', $alumnoId)->count();

        // Derivaciones
        $derivaciones = Derivacion::where('alumno_id', $alumnoId)->count();

        // Accidentes
        $accidentes = AccidenteEscolar::where('alumno_id', $alumnoId)->count();

        // Citaciones
        $citaciones = CitacionApoderado::where('alumno_id', $alumnoId)->count();

        // Novedades
        $novedades = NovedadInspectoria::where('alumno_id', $alumnoId)->count();

        // Atrasos / Asistencia
        $atrasos = AsistenciaEvento::where('alumno_id', $alumnoId)->count();

        // Retiros anticipados
        $retiros = RetiroAnticipado::where('alumno_id', $alumnoId)->count();

        // ======================================================
        //   GRÁFICO INCIDENTES POR TIPO
        // ======================================================
        $incidentesPorTipo = BitacoraIncidenteAlumno::where('alumno_id', $alumnoId)
            ->join('bitacora_incidentes', 'bitacora_incidentes.id', '=', 'bitacora_incidente_alumno.incidente_id')
            ->select(
                'bitacora_incidentes.tipo_incidente',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bitacora_incidentes.tipo_incidente')
            ->orderBy('total', 'desc')
            ->get();


        // AUDITORÍA
        logAuditoria(
            'view',
            'reporte_alumno',
            "Visualizó reporte de alumno ID {$alumnoId}"
        );

        return view('reportes.alumno', compact(
            'alumnos',
            'alumnoSeleccionado',
            'incidentes',
            'seguimientos',
            'derivaciones',
            'accidentes',
            'citaciones',
            'novedades',
            'atrasos',
            'retiros',
            'incidentesPorTipo'
        ));
    }
}
