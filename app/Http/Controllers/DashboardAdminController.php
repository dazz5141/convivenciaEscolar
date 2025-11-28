<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Funcionario;
use App\Models\Establecimiento;
use App\Models\BitacoraIncidente;
use App\Models\CitacionApoderado;
use App\Models\SeguimientoEmocional;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        /* ============================================================
           MÉTRICAS GLOBALES
        ============================================================ */

        $totalEstablecimientos = Establecimiento::count();
        $totalUsuarios = Usuario::count();
        $totalFuncionarios = Funcionario::count();
        $totalAlumnos = Alumno::count();

        $totalIncidentes = BitacoraIncidente::count();
        $totalCitaciones = CitacionApoderado::count();
        $totalSeguimientos = SeguimientoEmocional::count();


        /* ============================================================
           ÚLTIMOS INCIDENTES (con relaciones válidas)
        ============================================================ */

        $ultimosIncidentes = BitacoraIncidente::latest()
            ->take(15)
            ->with(['alumnos', 'curso', 'estado'])
            ->get();


        /* ============================================================
           RANKING DE COLEGIOS (últimos 30 días)
        ============================================================ */

        $incidentesPorColegio = BitacoraIncidente::where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('establecimiento_id, COUNT(*) as total')
            ->groupBy('establecimiento_id')
            ->orderByDesc('total')
            ->with('establecimiento')
            ->take(10)
            ->get();


        /* ============================================================
           LISTA DE ESTABLECIMIENTOS
        ============================================================ */

        $establecimientos = Establecimiento::orderBy('nombre')->get();


        return view('dashboard.roles.admin', compact(
            'totalEstablecimientos',
            'totalUsuarios',
            'totalFuncionarios',
            'totalAlumnos',

            'totalIncidentes',
            'totalCitaciones',
            'totalSeguimientos',

            'ultimosIncidentes',
            'incidentesPorColegio',
            'establecimientos'
        ));
    }
}
