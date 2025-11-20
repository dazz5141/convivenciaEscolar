<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Modelos Convivencia
use App\Models\BitacoraIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\MedidaRestaurativa;
use App\Models\Derivacion;

// Modelos Inspectoría
use App\Models\BitacoraIncidenteAlumno;
use App\Models\AccidenteEscolar;
use App\Models\NovedadInspectoria;
use App\Models\AsistenciaEvento;
use App\Models\RetiroAnticipado;
use App\Models\CitacionApoderado;

// Base
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\Establecimiento;

class DashboardReporteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rol = $user->rol_id;

        // Si no es admin general, filtro por establecimiento
        $establecimientoId = ($rol == 1)
            ? null
            : $user->establecimiento_id;

        // ===========================
        //    KPIs GENERALES
        // ===========================
        $totalIncidentesMes = BitacoraIncidente::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereMonth('fecha', now()->month)
            ->count();

        $totalSeguimientosMes = SeguimientoEmocional::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereMonth('fecha', now()->month)
            ->count();

        $totalDerivacionesMes = Derivacion::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereMonth('fecha', now()->month)
            ->count();

        $totalAccidentesMes = AccidenteEscolar::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereMonth('fecha', now()->month)
            ->count();


        // ===========================
        //    GRÁFICOS DINÁMICOS
        // ===========================

        // 📌 Incidentes por tipo (últimos 30 días)
        $incidentesPorTipo = BitacoraIncidente::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->where('fecha', '>=', now()->subDays(30))
            ->selectRaw('tipo_incidente, COUNT(*) as total')
            ->groupBy('tipo_incidente')
            ->get();

        // 📌 Tendencia mensual últimos 12 meses
        $tendenciaMensual = BitacoraIncidente::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as mes, COUNT(*) as total")
            ->where('fecha', '>=', now()->subYear())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // 📌 Heatmap (incidentes por día del mes actual)
        $heatmap = BitacoraIncidente::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereMonth('fecha', now()->month)
            ->selectRaw('DAY(fecha) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->get();

        // 📌 Seguimientos por nivel emocional
        $SeguimientosPorNivel = SeguimientoEmocional::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->selectRaw('nivel_emocional_id, COUNT(*) as total')
            ->groupBy('nivel_emocional_id')
            ->with('nivel')
            ->get();

        // 📌 Derivaciones por destino
        $derivacionesDestino = Derivacion::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->selectRaw('destino, COUNT(*) as total')
            ->groupBy('destino')
            ->get();


        // ===========================
        //    RANKINGS
        // ===========================

        // Cursos con más incidentes últimos 30 días
        $topCursos = BitacoraIncidenteAlumno::query()
            ->when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->whereHas('incidente', fn($q) =>
                $q->where('fecha', '>=', now()->subDays(30))
            )
            ->selectRaw('curso_id, COUNT(*) as total')
            ->groupBy('curso_id')
            ->orderByDesc('total')
            ->with('curso') 
            ->take(5)
            ->get();

        // Alumnos con más incidentes
        $topAlumnos = BitacoraIncidente::when($establecimientoId, fn($q) =>
                $q->where('establecimiento_id', $establecimientoId)
            )
            ->join('bitacora_incidente_alumno', 'bitacora_incidentes.id', '=', 'bitacora_incidente_alumno.incidente_id')
            ->selectRaw('alumno_id, COUNT(*) as total')
            ->groupBy('alumno_id')
            ->orderByDesc('total')
            ->with('alumnos')   // No te preocupes, funciona por la relación
            ->take(5)
            ->get();


        // ===========================
        //   Comparación entre sedes
        // ===========================
        $comparacionEstablecimientos = [];

        if ($rol == 1) { //Administrador General
            $comparacionEstablecimientos = BitacoraIncidente::selectRaw('establecimiento_id, COUNT(*) as total')
                ->groupBy('establecimiento_id')
                ->with('estado')
                ->get();
        }

        return view('reportes.dashboard', compact(
            'totalIncidentesMes',
            'totalSeguimientosMes',
            'totalDerivacionesMes',
            'totalAccidentesMes',
            'incidentesPorTipo',
            'tendenciaMensual',
            'heatmap',
            'SeguimientosPorNivel',
            'derivacionesDestino',
            'topCursos',
            'topAlumnos',
            'comparacionEstablecimientos',
            'rol'
        ));
    }
}
