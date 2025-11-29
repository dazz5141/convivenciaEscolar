<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Modelos
use App\Models\Funcionario;
use App\Models\BitacoraIncidente;
use App\Models\NovedadInspectoria;
use App\Models\CitacionApoderado;
use App\Models\AccidenteEscolar;
use App\Models\AsistenciaEvento;
use App\Models\RetiroAnticipado;
use App\Models\ConflictoFuncionario;
use App\Models\ConflictoApoderado;
use App\Models\DenunciaLeyKarin;

class FuncionarioReporteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ============================================
        // SI ES ADMIN GENERAL → NO FILTRAR ESTABLECIMIENTO
        // ============================================
        $establecimientoId = $user->establecimiento_id ?? null;

        
        // ======================================================
        //   PERMISO: VER REPORTE POR FUNCIONARIO
        // ======================================================
        if (!canAccess('reporte_funcionario', 'view')) {
            abort(403, 'No tienes permisos para ver el reporte por alumno.');
        }

        // ============================================
        // LISTA DE FUNCIONARIOS
        // ============================================
        $funcionariosQuery = Funcionario::orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre');

        if ($establecimientoId) {
            $funcionariosQuery->where('establecimiento_id', $establecimientoId);
        }

        $funcionarios = $funcionariosQuery->get();

        // Si solo estamos cargando la vista sin selección
        if (!$request->funcionario_id) {
            return view('reportes.funcionario', [
                'funcionarios' => $funcionarios,
                'funcionarioSeleccionado' => null
            ]);
        }

        $funcionarioId = $request->funcionario_id;
        $funcionarioSeleccionado = Funcionario::findOrFail($funcionarioId);

        // ============================================
        // FILTRO BASE PARA TODOS LOS MÓDULOS
        // ============================================
        $filter = function ($query, $column = 'establecimiento_id') use ($establecimientoId) {
            if ($establecimientoId) {
                $query->where($column, $establecimientoId);
            }
        };

        // ============================================
        // KPIs DEL FUNCIONARIO
        // ============================================

        // 1. Incidentes reportados
        $incidentesReportados = BitacoraIncidente::where('reportado_por', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 2. Novedades
        $novedades = NovedadInspectoria::where('funcionario_id', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 3. Citaciones
        $citaciones = CitacionApoderado::where('funcionario_id', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 4. Accidentes
        $accidentes = AccidenteEscolar::where('registrado_por', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 5. Atrasos / asistencia
        $atrasos = AsistenciaEvento::where('registrado_por', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 6. Retiros anticipados
        $retiros = RetiroAnticipado::where('entregado_por', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->count();

        // 7. Conflictos entre funcionarios
        $conflictosFuncionarios = ConflictoFuncionario::when(
                $establecimientoId,
                fn($q) => $q->where('establecimiento_id', $establecimientoId)
            )
            ->where(function ($q) use ($funcionarioId) {
                $q->where('funcionario_1_id', $funcionarioId)
                  ->orWhere('funcionario_2_id', $funcionarioId)
                  ->orWhere('registrado_por_id', $funcionarioId);
            })
            ->count();

        // 8. Conflictos con apoderados
        $conflictosApoderados = ConflictoApoderado::when(
                $establecimientoId,
                fn($q) => $q->where('establecimiento_id', $establecimientoId)
            )
            ->where('funcionario_id', $funcionarioId)
            ->count();

        // 9. Casos Ley Karin por RUT
        $rutFuncionario = $funcionarioSeleccionado->run;

        $leyKarin = DenunciaLeyKarin::when(
                $establecimientoId,
                fn($q) => $q->where('establecimiento_id', $establecimientoId)
            )
            ->where(function ($q) use ($rutFuncionario) {
                $q->where('denunciante_rut', $rutFuncionario)
                  ->orWhere('denunciado_rut', $rutFuncionario)
                  ->orWhere('victima_rut', $rutFuncionario);
            })
            ->count();

        // ============================================
        // GRÁFICO DE NOVEDADES POR TIPO
        // ============================================
        $novedadesPorTipo = NovedadInspectoria::where('funcionario_id', $funcionarioId)
            ->when($establecimientoId, fn($q) => $q->where('establecimiento_id', $establecimientoId))
            ->join('tipos_novedad', 'tipos_novedad.id', '=', 'novedades_inspectoria.tipo_novedad_id')
            ->select('tipos_novedad.nombre as tipo', \DB::raw('COUNT(*) as total'))
            ->groupBy('tipos_novedad.nombre')
            ->get();

        // AUDITORÍA
        logAuditoria(
            'view',
            'reporte_funcionario',
            "Visualizó reporte del funcionario ID {$funcionarioId}"
        );

        return view('reportes.funcionario', compact(
            'funcionarios',
            'funcionarioSeleccionado',
            'incidentesReportados',
            'novedades',
            'citaciones',
            'accidentes',
            'atrasos',
            'retiros',
            'conflictosFuncionarios',
            'conflictosApoderados',
            'leyKarin',
            'novedadesPorTipo'
        ));
    }
}
