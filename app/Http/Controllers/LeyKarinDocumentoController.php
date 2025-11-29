<?php

namespace App\Http\Controllers;

use App\Models\DenunciaLeyKarin;
use App\Models\DocumentoAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeyKarinDocumentoController extends Controller
{
    /* ================================================================
       LISTADO DE DOCUMENTOS
    ================================================================= */
    public function index(DenunciaLeyKarin $denuncia)
    {
        /* --- Permiso para VER documentos --- */
        if (!canAccess('denuncias', 'view')) {
            abort(403, 'No tienes permisos para ver documentos relacionados a denuncias.');
        }

        /* --- Seguridad multi-colegio --- */
        if ($denuncia->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado. La denuncia pertenece a otro establecimiento.');
        }

        /* --- Solo documentos activos --- */
        $documentos = DocumentoAdjunto::where('activo', 1)
            ->where('entidad_type', DenunciaLeyKarin::class)
            ->where('entidad_id', $denuncia->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modulos.ley-karin.documentos.index', compact('denuncia', 'documentos'));
    }



    /* ================================================================
       SUBIR DOCUMENTO
    ================================================================= */
    public function store(Request $request, DenunciaLeyKarin $denuncia)
    {
        /* --- Permiso para CREAR/EDITAR documentos --- */
        if (!canAccess('denuncias', 'edit')) {
            abort(403, 'No tienes permisos para adjuntar documentos.');
        }

        /* --- Seguridad multi-colegio --- */
        if ($denuncia->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        /* --- Validación de archivo --- */
        $request->validate([
            'archivo' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $file = $request->file('archivo');

        /* --- Guardado físico --- */
        $ruta = $file->store("documentos/leykarin/{$denuncia->id}", 'public');

        /* --- Registro en BD --- */
        $documento = DocumentoAdjunto::create([
            'entidad_type'       => DenunciaLeyKarin::class,
            'entidad_id'         => $denuncia->id,
            'nombre_archivo'     => $file->getClientOriginalName(),
            'ruta_archivo'       => "storage/" . $ruta,
            'subido_por'         => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
            'activo'             => 1,
        ]);

        /* ---------------------------------------------------------
           AUDITORÍA: CREACIÓN DE DOCUMENTO
        --------------------------------------------------------- */
        logAuditoria(
            accion: 'create',
            modulo: 'leykarin_documentos',
            detalle: "Subió documento ID {$documento->id} a la denuncia ID {$denuncia->id}",
            establecimiento_id: session('establecimiento_id')
        );

        return redirect()
            ->route('leykarin.documentos.index', $denuncia)
            ->with('success', 'Documento adjuntado correctamente.');
    }



    /* ================================================================
       INVALIDAR DOCUMENTO (DESHABILITAR)
    ================================================================= */
    public function disable($id)
    {
        /* --- Permiso para EDITAR documentos --- */
        if (!canAccess('denuncias', 'edit')) {
            abort(403, 'No tienes permisos para invalidar documentos.');
        }

        $documento = DocumentoAdjunto::findOrFail($id);

        /* --- Seguridad multi-colegio --- */
        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        $documento->update([
            'activo'         => 0,
            'invalidado_por' => Auth::user()->funcionario_id,
            'invalidado_en'  => now(),
        ]);

        /* ---------------------------------------------------------
           AUDITORÍA: INVALIDAR DOCUMENTO
        --------------------------------------------------------- */
        logAuditoria(
            accion: 'update',
            modulo: 'leykarin_documentos',
            detalle: "Invalidó documento ID {$documento->id}",
            establecimiento_id: session('establecimiento_id')
        );

        return back()->with('success', 'Documento invalidado correctamente.');
    }



    /* ================================================================
       HABILITAR DOCUMENTO (OPCIONAL)
    ================================================================= */
    public function enable($id)
    {
        /* --- Permiso para EDITAR documentos --- */
        if (!canAccess('denuncias', 'edit')) {
            abort(403, 'No tienes permisos para habilitar documentos.');
        }

        $documento = DocumentoAdjunto::findOrFail($id);

        /* --- Seguridad multi-colegio --- */
        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        $documento->update([
            'activo'         => 1,
            'invalidado_por' => null,
            'invalidado_en'  => null,
        ]);

        /* ---------------------------------------------------------
           AUDITORÍA: HABILITAR DOCUMENTO
        --------------------------------------------------------- */
        logAuditoria(
            accion: 'update',
            modulo: 'leykarin_documentos',
            detalle: "Habilitó nuevamente documento ID {$documento->id}",
            establecimiento_id: session('establecimiento_id')
        );
        
        return back()->with('success', 'Documento habilitado nuevamente.');
    }
}
