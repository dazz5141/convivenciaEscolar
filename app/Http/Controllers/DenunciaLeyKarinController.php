<?php

namespace App\Http\Controllers;

use App\Models\DenunciaLeyKarin;
use App\Models\ConflictoFuncionario;
use App\Models\ConflictoApoderado;
use App\Models\Funcionario;
use App\Models\Apoderado;
use App\Models\TipoDenunciaLeyKarin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DenunciaLeyKarinController extends Controller
{
    /**
     * Listado de denuncias
     */
    public function index()
    {
        $establecimientoId = session('establecimiento_id');

        $denuncias = DenunciaLeyKarin::where('establecimiento_id', $establecimientoId)
            ->latest()
            ->paginate(15);

        return view('modulos.ley-karin.denuncias.index', compact('denuncias'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $establecimientoId = session('establecimiento_id');

        // Conflictos existentes para vincular la denuncia
        $conflictos_funcionarios = ConflictoFuncionario::where('establecimiento_id', $establecimientoId)->get();
        $conflictos_apoderados  = ConflictoApoderado::where('establecimiento_id', $establecimientoId)->get();

        // Tipos de denuncia
        $tipos = TipoDenunciaLeyKarin::orderBy('nombre')->get();

        return view('modulos.ley-karin.denuncias.create', compact(
            'conflictos_funcionarios',
            'conflictos_apoderados',
            'tipos'
        ));
    }

    /**
     * Guardar denuncia
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_denuncia'    => 'required|date',
            'tipo_denuncia_id'  => 'required|integer',
            'descripcion'       => 'required|string|min:10',
            'denunciante_id'    => 'required|integer',
            'denunciado_id'     => 'required|integer',
        ]);

        $establecimientoId = session('establecimiento_id');

        // Detectar si es funcionario o apoderado
        $denunciante = Funcionario::find($request->denunciante_id)
                        ?? Apoderado::find($request->denunciante_id);

        $denunciado  = Funcionario::find($request->denunciado_id)
                        ?? Apoderado::find($request->denunciado_id);

        $data = [
            'establecimiento_id' => $establecimientoId,
            'registrado_por_id'  => Auth::id(),

            'fecha_denuncia'     => $request->fecha_denuncia,
            'descripcion'        => $request->descripcion,
            'confidencial'       => 1,

            // Tipo de denuncia (FK)
            'tipo_denuncia_id'   => $request->tipo_denuncia_id,

            /* ==============================
                DATOS DEL DENUNCIANTE
            =============================== */
            'denunciante_nombre' => $denunciante?->nombre_completo,
            'denunciante_rut'    => $denunciante->run ?? null,
            'denunciante_cargo'  => $denunciante instanceof Funcionario 
                                    ? $denunciante->cargo?->nombre 
                                    : 'Apoderado',
            'denunciante_area'   => null, // no existe campo área actualmente

            /* ==============================
                DATOS DEL DENUNCIADO
            =============================== */
            'denunciado_nombre' => $denunciado?->nombre_completo,
            'denunciado_rut'    => $denunciado->run ?? null,
            'denunciado_cargo'  => $denunciado instanceof Funcionario 
                                    ? $denunciado->cargo?->nombre 
                                    : 'Apoderado',
            'denunciado_area'   => null,

            /* ==============================
                POLIMÓRFICO
            =============================== */
            'conflictable_type' => $request->conflictable_type ?: null,
            'conflictable_id'   => $request->conflictable_id ?: null,
        ];

        $denuncia = DenunciaLeyKarin::create($data);

        return redirect()
            ->route('leykarin.denuncias.show', $denuncia)
            ->with('success', 'La denuncia fue registrada correctamente.');
    }

    /**
     * Mostrar detalle
     */
    public function show(DenunciaLeyKarin $denuncia)
    {
        $this->validarEstablecimiento($denuncia);

        return view('modulos.ley-karin.denuncias.show', compact('denuncia'));
    }

    /**
     * Formulario de edición
     */
    public function edit(DenunciaLeyKarin $denuncia)
    {
        $this->validarEstablecimiento($denuncia);

        return view('modulos.ley-karin.denuncias.edit', compact('denuncia'));
    }

    /**
     * Actualizar denuncia
     */
    public function update(Request $request, DenunciaLeyKarin $denuncia)
    {
        $this->validarEstablecimiento($denuncia);

        $request->validate([
            'descripcion' => 'required|string|min:10',
        ]);

        $denuncia->update([
            'descripcion' => $request->descripcion,
        ]);

        return redirect()
            ->route('leykarin.denuncias.show', $denuncia)
            ->with('success', 'Denuncia actualizada correctamente.');
    }

    /**
     * Seguridad multi-colegio
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            if (app()->environment('local')) {
                \Log::warning("⚠️ [DEV] El modelo ".get_class($modelo)." (ID: {$modelo->id}) no tiene establecimiento_id definido.");
                return;
            } else {
                abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
            }
        }

        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }

    public function documentos(DenunciaLeyKarin $denuncia)
    {
        return view('modulos.ley-karin.denuncias.documentos', compact('denuncia'));
    }
}
