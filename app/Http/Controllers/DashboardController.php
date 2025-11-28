<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BitacoraIncidente;
use App\Models\CitacionApoderado;
use App\Models\SeguimientoEmocional;
use App\Models\EstadoSeguimientoEmocional;
use App\Models\Alumno;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // =======================================================
        // 1) ADMIN GENERAL (Rol 1) → Dashboard exclusivo
        // =======================================================
        if ($usuario->rol_id == 1) {
            return redirect()->route('dashboard.admin');
        }

        // =======================================================
        // 2) Datos generales para todos los roles
        // =======================================================
        $establecimientoId = $usuario->establecimiento_id;

        // Incidentes de hoy
        $incidentesHoy = BitacoraIncidente::delColegio($establecimientoId)
            ->whereDate('fecha', Carbon::today())
            ->count();

        // Alumnos activos (filtrados por curso del mismo establecimiento)
        $alumnosActivos = Alumno::whereHas('curso', function ($q) use ($establecimientoId) {
                $q->where('establecimiento_id', $establecimientoId);
            })
            ->activos()
            ->count();

        // =======================================================
        // 3) Clasificación de roles
        // =======================================================
        $rolesCompletos = [2, 3, 4, 8, 9];
        $rolesReducidos = [5, 6, 7];

        // =======================================================
        // 4) ROLES REDUCIDOS (5, 6, 7)
        // =======================================================
        if (in_array($usuario->rol_id, $rolesReducidos)) {

            $ultimosIncidentes = BitacoraIncidente::delColegio($establecimientoId)
                ->latest()
                ->take(5)
                ->with(['alumnos', 'curso', 'estado'])
                ->get();

            // Extra solo para Psicólogo — rol 6
            $seguimientosPendientes = null;

            if ($usuario->rol_id == 6) {

                $pendienteId = EstadoSeguimientoEmocional::where('nombre', 'Pendiente')->value('id');

                $seguimientosPendientes = SeguimientoEmocional::delColegio($establecimientoId)
                    ->where('estado_id', $pendienteId)
                    ->with(['alumno.curso'])
                    ->take(5)
                    ->get();
            }

            return view('dashboard.roles.establecimiento', compact(
                'incidentesHoy',
                'alumnosActivos',
                'ultimosIncidentes',
                'seguimientosPendientes'
            ));
        }

        // =======================================================
        // 5) ROLES COMPLETOS (2, 3, 4, 8, 9)
        // =======================================================
        $totalIncidentesMes = BitacoraIncidente::delColegio($establecimientoId)
            ->whereMonth('fecha', Carbon::now()->month)
            ->count();

        $citacionesPendientes = CitacionApoderado::delColegio($establecimientoId)
            ->where('estado_id', 1)
            ->count();

        $incidentesPorEstado = BitacoraIncidente::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('estado_id, COUNT(*) as total')
            ->groupBy('estado_id')
            ->with('estado')
            ->get();

        $seguimientosPorNivel = SeguimientoEmocional::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('nivel_emocional_id, COUNT(*) as total')
            ->groupBy('nivel_emocional_id')
            ->with('nivel')
            ->get();

        $topCursos = BitacoraIncidente::delColegio($establecimientoId)
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('curso_id, COUNT(*) as total')
            ->groupBy('curso_id')
            ->orderByDesc('total')
            ->with('curso')
            ->take(5)
            ->get();

        $ultimosIncidentes = BitacoraIncidente::delColegio($establecimientoId)
            ->latest()
            ->take(10)
            ->with(['alumnos', 'curso', 'estado'])
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
