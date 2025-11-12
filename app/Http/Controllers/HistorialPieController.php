<?php

namespace App\Http\Controllers;

use App\Models\EstudiantePIE;
use App\Models\IntervencionPIE;
use App\Models\InformePIE;
use App\Models\PlanIndividualPIE;
use App\Models\DerivacionPIE;
use Illuminate\Support\Str;

class HistorialPieController extends Controller
{
    /**
     * Mostrar historial completo del estudiante PIE
     */
    public function index(EstudiantePIE $estudiantePIE)
    {
        $estudiantePIE->load([
            'alumno.curso',
            'intervenciones.profesional.cargo',
            'informes',
            'planes',
            'derivaciones',
        ]);

        return view('modulos.pie.historial.index', compact('estudiantePIE'));
    }

    /**
     * Mostrar un evento específico del historial (intervención, informe, plan o derivación)
     */
    public function show($tipo, $id)
    {
        switch ($tipo) {

            case 'intervencion':
                $modelo = IntervencionPIE::with(['estudiante.alumno', 'profesional.funcionario.cargo'])
                    ->findOrFail($id);
                $titulo = "Detalle de Intervención PIE";
                break;

            case 'informe':
                $modelo = InformePIE::with(['estudiante.alumno'])
                    ->findOrFail($id);
                $titulo = "Detalle de Informe PIE";
                break;

            case 'plan':
                $modelo = PlanIndividualPIE::with(['estudiante.alumno'])
                    ->findOrFail($id);
                $titulo = "Detalle de Plan Individual PIE";
                break;

            case 'derivacion':
                $modelo = DerivacionPIE::with(['estudiante.alumno'])
                    ->findOrFail($id);
                $titulo = "Detalle de Derivación PIE";
                break;

            default:
                abort(404, "Tipo de historial no válido.");
        }

        return view('modulos.pie.historial.show', [
            'data' => $modelo,
            'tipo' => $tipo,
            'titulo' => $titulo
        ]);
    }
}
