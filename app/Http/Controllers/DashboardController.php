<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BitacoraIncidente;
use App\Models\CitacionApoderado;
use App\Models\SeguimientoEmocional;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $establecimientoId = Auth::user()->establecimiento_id;

        // KPIs principales
        $incidentesHoy = BitacoraIncidente::delColegio($establecimientoId)
            ->whereDate('fecha', Carbon::today())
            ->count();

        $totalIncidentesMes = BitacoraIncidente::delColegio($establecimientoId)
            ->whereMonth('fecha', Carbon::now()->month)
            ->count();

        $citacionesPendientes = CitacionApoderado::delColegio($establecimientoId)
            ->where('estado_id', 1) // pendiente
            ->count();

        $alumnosActivos = \App\Models\Alumno::activos()->count();


        /* Incidentes agrupados por estado (últimos 30 días) */
        $incidentesPorEstado = BitacoraIncidente::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('estado_id, COUNT(*) as total')
            ->groupBy('estado_id')
            ->with('estado')
            ->get();


        /* Seguimientos agrupados por nivel emocional */
        $seguimientosPorNivel = SeguimientoEmocional::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('nivel_emocional_id, COUNT(*) as total')
            ->groupBy('nivel_emocional_id')
            ->with('nivel')
            ->get();


        /* Top 5 cursos con más incidentes */
        $topCursos = BitacoraIncidente::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('curso_id, COUNT(*) as total')
            ->groupBy('curso_id')
            ->orderByDesc('total')
            ->with('curso')
            ->take(5)
            ->get();


        /* Últimos incidentes */
        $ultimosIncidentes = BitacoraIncidente::delColegio($establecimientoId)
            ->latest()
            ->take(10)
            ->with(['alumno', 'curso', 'estado'])
            ->get();


        return view('dashboard.roles.establecimiento', compact(
            'incidentesHoy',
            'totalIncidentesMes',
            'citacionesPendientes',
            'alumnosActivos',
            'incidentesPorEstado',
            'seguimientosPorNivel',
            'topCursos',
            'ultimosIncidentes'
        ));
    }
}
