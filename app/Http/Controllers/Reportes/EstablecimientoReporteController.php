<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Modelos
use App\Models\Establecimiento;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Funcionario;
use App\Models\BitacoraIncidente;
use App\Models\SeguimientoEmocional;
use App\Models\Derivacion;
use App\Models\MedidaRestaurativa;
use App\Models\NovedadInspectoria;
use App\Models\AsistenciaEvento;
use App\Models\RetiroAnticipado;
use App\Models\AccidenteEscolar;
use App\Models\CitacionApoderado;
use App\Models\ConflictoFuncionario;
use App\Models\ConflictoApoderado;
use App\Models\DenunciaLeyKarin;

class EstablecimientoReporteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Si el usuario está asociado a un establecimiento, usamos ese.
        // Si es admin general (sin establecimiento_id), permitimos seleccionar por GET
        $establecimientoId = $user->establecimiento_id;

        if (!$establecimientoId) {
            if ($request->filled('establecimiento_id')) {
                $establecimientoId = (int) $request->establecimiento_id;
            } else {
                // Tomamos el primer establecimiento activo (para no dejarlo en blanco)
                $establecimientoId = Establecimiento::where('activo', 1)->value('id');
            }
        }

        // Si definitivamente no hay establecimientos
        if (!$establecimientoId) {
            return view('reportes.establecimiento', [
                'establecimiento'        => null,
                'establecimientosSelect' => Establecimiento::orderBy('nombre')->get(),
                'establecimientoId'      => null,

                'totalAlumnos'        => 0,
                'totalFuncionarios'   => 0,
                'totalCursos'         => 0,
                'totalIncidentes'     => 0,
                'totalSeguimientos'   => 0,
                'totalDerivaciones'   => 0,
                'totalMedidas'        => 0,
                'totalNovedades'      => 0,
                'totalAsistencia'     => 0,
                'totalRetiros'        => 0,
                'totalAccidentes'     => 0,
                'totalCitaciones'     => 0,
                'totalConflictosFunc' => 0,
                'totalConflictosApod' => 0,
                'totalLeyKarin'       => 0,

                'incidentesPorMes'    => collect(),
                'incidentesPorTipo'   => collect(),
                'topCursosIncidentes' => collect(),
            ]);
        }

        $establecimiento = Establecimiento::with(['dependencia', 'comuna'])
            ->findOrFail($establecimientoId);

        // Para el selector de establecimiento (sobre todo útil para admin general)
        $establecimientosSelect = Establecimiento::orderBy('nombre')->get();

        // ==========================
        //   DIMENSIÓN COMUNIDAD
        // ==========================
        $totalCursos = Curso::where('establecimiento_id', $establecimientoId)->count();

        $totalAlumnos = Alumno::whereHas('curso', function ($q) use ($establecimientoId) {
                $q->where('establecimiento_id', $establecimientoId);
            })
            ->count();

        $totalFuncionarios = Funcionario::where('establecimiento_id', $establecimientoId)->count();

        // ==========================
        //   CONVIVENCIA / INSPECTORÍA
        // ==========================
        $totalIncidentes = BitacoraIncidente::where('establecimiento_id', $establecimientoId)->count();

        $totalSeguimientos = SeguimientoEmocional::where('establecimiento_id', $establecimientoId)->count();

        $totalDerivaciones = Derivacion::where('establecimiento_id', $establecimientoId)->count();

        $totalMedidas = MedidaRestaurativa::where('establecimiento_id', $establecimientoId)->count();

        $totalNovedades = NovedadInspectoria::where('establecimiento_id', $establecimientoId)->count();

        $totalAsistencia = AsistenciaEvento::where('establecimiento_id', $establecimientoId)->count();

        $totalRetiros = RetiroAnticipado::where('establecimiento_id', $establecimientoId)->count();

        $totalAccidentes = AccidenteEscolar::where('establecimiento_id', $establecimientoId)->count();

        $totalCitaciones = CitacionApoderado::where('establecimiento_id', $establecimientoId)->count();

        $totalConflictosFunc = ConflictoFuncionario::where('establecimiento_id', $establecimientoId)->count();

        $totalConflictosApod = ConflictoApoderado::where('establecimiento_id', $establecimientoId)->count();

        $totalLeyKarin = DenunciaLeyKarin::where('establecimiento_id', $establecimientoId)->count();

        // ==========================
        //   GRÁFICOS
        // ==========================

        // Incidentes por mes
        $incidentesPorMes = BitacoraIncidente::where('establecimiento_id', $establecimientoId)
            ->selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Incidentes por tipo
        $incidentesPorTipo = BitacoraIncidente::where('establecimiento_id', $establecimientoId)
            ->select('tipo_incidente', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_incidente')
            ->orderBy('total', 'desc')
            ->get();

        // Top 5 cursos con más incidentes
        $rankingCursos = BitacoraIncidente::where('bitacora_incidentes.establecimiento_id', $establecimientoId)
            ->join('cursos', 'cursos.id', '=', 'bitacora_incidentes.curso_id')
            ->select(
                'cursos.id',
                DB::raw("CONCAT(cursos.nivel,' ',cursos.letra) AS nombre_curso"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('cursos.id', 'nombre_curso')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('reportes.establecimiento', compact(
            'establecimiento',
            'establecimientoId',
            'establecimientosSelect',
            'totalAlumnos',
            'totalFuncionarios',
            'totalCursos',
            'totalIncidentes',
            'totalSeguimientos',
            'totalDerivaciones',
            'totalMedidas',
            'totalNovedades',
            'totalAsistencia',
            'totalRetiros',
            'totalAccidentes',
            'totalCitaciones',
            'totalConflictosFunc',
            'totalConflictosApod',
            'totalLeyKarin',
            'incidentesPorMes',
            'incidentesPorTipo',
            'rankingCursos'
        ));
    }
}
