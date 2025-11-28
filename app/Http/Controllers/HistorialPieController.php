<?php

namespace App\Http\Controllers;

use App\Models\EstudiantePIE;
use App\Models\IntervencionPIE;
use App\Models\InformePIE;
use App\Models\PlanIndividualPIE;
use App\Models\DerivacionPIE;
use Illuminate\Http\Request;

class HistorialPieController extends Controller
{
    /**
     * Mostrar historial completo del estudiante PIE
     */
    public function index(EstudiantePIE $estudiantePIE)
    {
        // ============================
        // PERMISO: VER HISTORIAL
        // ============================
        if (!canAccess('pie', 'view')) {
            abort(403, 'No tienes permisos para ver historial PIE.');
        }

        // ============================
        // VALIDAR ESTABLECIMIENTO
        // ============================
        $this->validarEstablecimiento($estudiantePIE);

        // ============================
        // CARGAR RELACIONES
        // ============================
        $estudiantePIE->load([
            'alumno.curso',
            'intervenciones.profesional.funcionario',
            'informes',
            'planes',
            'derivaciones',
        ]);

        return view('modulos.pie.historial.index', [
            'estudiantePie' => $estudiantePIE
        ]);
    }



    /**
     * Mostrar un evento específico del historial (intervención, informe, plan o derivación)
     */
    public function show($tipo, $id)
    {
        // ============================
        // PERMISO: VER DETALLE
        // ============================
        if (!canAccess('pie', 'view')) {
            abort(403, 'No tienes permisos para ver este registro.');
        }

        $modelo = null;
        $titulo = '';

        switch ($tipo) {

            case 'intervencion':
                $modelo = IntervencionPIE::with([
                        'estudiante.alumno.curso',
                        'profesional.funcionario'
                    ])
                    ->findOrFail($id);
                $titulo = "Detalle de Intervención PIE";
                break;

            case 'informe':
                $modelo = InformePIE::with([
                        'estudiante.alumno.curso'
                    ])
                    ->findOrFail($id);
                $titulo = "Detalle de Informe PIE";
                break;

            case 'plan':
                $modelo = PlanIndividualPIE::with([
                        'estudiante.alumno.curso'
                    ])
                    ->findOrFail($id);
                $titulo = "Detalle de Plan Individual PIE";
                break;

            case 'derivacion':
                $modelo = DerivacionPIE::with([
                        'estudiante.alumno.curso'
                    ])
                    ->findOrFail($id);
                $titulo = "Detalle de Derivación PIE";
                break;

            default:
                abort(404, "Tipo de historial no válido.");
        }

        // ============================
        // VALIDAR ESTABLECIMIENTO
        // ============================
        $this->validarEstablecimiento($modelo);

        return view('modulos.pie.historial.show', [
            'data'   => $modelo,
            'tipo'   => $tipo,
            'titulo' => $titulo
        ]);
    }



    /**
     * Validación multicolegio
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] Modelo ".get_class($modelo)." (ID: {$modelo->id}) no tiene establecimiento asignado.");
                return;
            }
            abort(403, 'Registro sin establecimiento válido.');
        }

        if ((int)$establecimientoModelo !== (int)$establecimientoSesion) {
            abort(403, 'No tienes acceso a este registro.');
        }
    }
}
