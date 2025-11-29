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
     * ============================================================
     * LISTADO DE DENUNCIAS
     * ============================================================
     */
    public function index()
    {
        if (!canAccess('denuncias','view')) {
            abort(403, 'No tienes permisos para ver denuncias Ley Karin.');
        }

        $establecimientoId = session('establecimiento_id');

        $denuncias = DenunciaLeyKarin::where('establecimiento_id', $establecimientoId)
            ->latest()
            ->paginate(15);

        return view('modulos.ley-karin.denuncias.index', compact('denuncias'));
    }



    /**
     * ============================================================
     * FORMULARIO DE CREACIÓN
     * ============================================================
     */
    public function create()
    {
        if (!canAccess('denuncias','create')) {
            abort(403, 'No tienes permisos para registrar denuncias.');
        }

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
     * ============================================================
     * GUARDAR DENUNCIA
     * ============================================================
     */
    public function store(Request $request)
    {
        if (!canAccess('denuncias','create')) {
            abort(403, 'No tienes permisos para registrar denuncias.');
        }

        $request->validate([
            'fecha_denuncia'    => 'required|date',
            'tipo_denuncia_id'  => 'required|exists:tipos_denuncia_ley_karin,id',
            'descripcion'       => 'required|string|min:10',
            'denunciante_id'    => 'required|integer',
            'denunciado_id'     => 'required|integer',
            'conflictable_type' => 'nullable|string',
            'conflictable_id'   => 'nullable|integer',
        ]);

        $establecimientoId = session('establecimiento_id');

        /* ============================================================
           VALIDAR ORIGEN DE DENUNCIANTE Y DENUNCIADO
        ============================================================ */

        /* VALIDAR DENUNCIANTE */
        $denunciante = Funcionario::find($request->denunciante_id);

        if ($denunciante) {
            if ($denunciante->establecimiento_id != $establecimientoId) {
                abort(403, 'El denunciante no pertenece al establecimiento actual.');
            }
        } else {
            // Es apoderado
            $denunciante = Apoderado::find($request->denunciante_id);
            if (!$denunciante || $denunciante->establecimiento_id != $establecimientoId) {
                abort(403, 'El denunciante no pertenece al establecimiento actual.');
            }
        }

        /* VALIDAR DENUNCIADO */
        $denunciado = Funcionario::find($request->denunciado_id);

        if ($denunciado) {
            if ($denunciado->establecimiento_id != $establecimientoId) {
                abort(403, 'El denunciado no pertenece al establecimiento actual.');
            }
        } else {
            // Es apoderado
            $denunciado = Apoderado::find($request->denunciado_id);
            if (!$denunciado || $denunciado->establecimiento_id != $establecimientoId) {
                abort(403, 'El denunciado no pertenece al establecimiento actual.');
            }
        }


        /* ============================================================
           VALIDAR CONFLICTO ASOCIADO (POLIMÓRFICO)
        ============================================================ */

        if ($request->conflictable_id && $request->conflictable_type) {

            $conflictoValido = match ($request->conflictable_type) {
                ConflictoFuncionario::class => ConflictoFuncionario::where('establecimiento_id', $establecimientoId)
                    ->find($request->conflictable_id),
                
                ConflictoApoderado::class => ConflictoApoderado::where('establecimiento_id', $establecimientoId)
                    ->find($request->conflictable_id),

                default => null,
            };

            if (!$conflictoValido) {
                abort(403, 'El conflicto asociado no pertenece al establecimiento.');
            }
        }


        /* ============================================================
           REGISTRO FINAL
        ============================================================ */

        $data = [
            'establecimiento_id' => $establecimientoId,
            'registrado_por_id'  => Auth::id(),

            'fecha_denuncia'     => $request->fecha_denuncia,
            'descripcion'        => $request->descripcion,
            'confidencial'       => 1,

            'tipo_denuncia_id'   => $request->tipo_denuncia_id,

            /* --------------- DENUNCIANTE --------------- */
            'denunciante_nombre' => $denunciante->nombre_completo,
            'denunciante_rut'    => $denunciante->run ?? null,
            'denunciante_cargo'  => $denunciante instanceof Funcionario 
                                        ? ($denunciante->cargo?->nombre ?? 'Funcionario')
                                        : 'Apoderado',
            'denunciante_area'   => null,

            /* --------------- DENUNCIADO --------------- */
            'denunciado_nombre' => $denunciado->nombre_completo,
            'denunciado_rut'    => $denunciado->run ?? null,
            'denunciado_cargo'  => $denunciado instanceof Funcionario 
                                        ? ($denunciado->cargo?->nombre ?? 'Funcionario')
                                        : 'Apoderado',
            'denunciado_area'   => null,

            /* --------------- POLIMÓRFICO --------------- */
            'conflictable_type' => $request->conflictable_type ?: null,
            'conflictable_id'   => $request->conflictable_id ?: null,
        ];

        $denuncia = DenunciaLeyKarin::create($data);

        /* ============================================================
        AUDITORÍA: CREAR DENUNCIA LEY KARIN
        ============================================================ */
        logAuditoria(
            accion: 'create',
            modulo: 'denuncias',
            detalle: 'Se registró una nueva denuncia Ley Karin ID ' . $denuncia->id,
            establecimiento_id: $denuncia->establecimiento_id
        );

        return redirect()
            ->route('leykarin.denuncias.show', $denuncia)
            ->with('success', 'La denuncia fue registrada correctamente.');
    }



    /**
     * ============================================================
     * MOSTRAR DETALLE
     * ============================================================
     */
    public function show(DenunciaLeyKarin $denuncia)
    {
        if (!canAccess('denuncias','view')) {
            abort(403, 'No tienes permisos para ver denuncias.');
        }

        $this->validarEstablecimiento($denuncia);

        return view('modulos.ley-karin.denuncias.show', compact('denuncia'));
    }



    /**
     * ============================================================
     * FORMULARIO EDITAR
     * ============================================================
     */
    public function edit(DenunciaLeyKarin $denuncia)
    {
        if (!canAccess('denuncias','edit')) {
            abort(403, 'No tienes permisos para editar denuncias.');
        }

        $this->validarEstablecimiento($denuncia);

        return view('modulos.ley-karin.denuncias.edit', compact('denuncia'));
    }



    /**
     * ============================================================
     * ACTUALIZAR
     * ============================================================
     */
    public function update(Request $request, DenunciaLeyKarin $denuncia)
    {
        if (!canAccess('denuncias','edit')) {
            abort(403, 'No tienes permisos para editar denuncias.');
        }

        $this->validarEstablecimiento($denuncia);

        $request->validate([
            'descripcion' => 'required|string|min:10',
        ]);

        $denuncia->update([
            'descripcion' => $request->descripcion,
        ]);

        /* ============================================================
        AUDITORÍA: ACTUALIZAR DENUNCIA LEY KARIN
        ============================================================ */
        logAuditoria(
            accion: 'update',
            modulo: 'denuncias',
            detalle: 'Se actualizó la denuncia Ley Karin ID ' . $denuncia->id,
            establecimiento_id: $denuncia->establecimiento_id
        );

        return redirect()
            ->route('leykarin.denuncias.show', $denuncia)
            ->with('success', 'Denuncia actualizada correctamente.');
    }



    /**
     * ============================================================
     * VALIDAR MULTICOLEGIO
     * ============================================================
     */
    private function validarEstablecimiento($modelo)
    {
        $establecimientoSesion = session('establecimiento_id');
        $establecimientoModelo = $modelo->establecimiento_id ?? null;

        if (!$establecimientoModelo) {
            abort(403, 'Acceso denegado: el registro no tiene establecimiento asignado.');
        }

        if ($establecimientoModelo != $establecimientoSesion) {
            abort(403, 'Acceso denegado: el registro pertenece a otro establecimiento.');
        }
    }



    /**
     * ============================================================
     * DOCUMENTOS
     * ============================================================
     */
    public function documentos(DenunciaLeyKarin $denuncia)
    {
        $this->validarEstablecimiento($denuncia);

        return view('modulos.ley-karin.denuncias.documentos', compact('denuncia'));
    }
}
