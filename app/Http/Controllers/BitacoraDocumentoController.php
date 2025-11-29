<?php

namespace App\Http\Controllers;

use App\Models\BitacoraIncidente;
use App\Models\DocumentoAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraDocumentoController extends Controller
{
    /**
     * Listado de documentos de un incidente.
     */
    public function index(BitacoraIncidente $incidente)
    {
        // Seguridad multicolegio
        if ($incidente->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        $documentos = DocumentoAdjunto::where('activo', 1)
            ->where('entidad_type', BitacoraIncidente::class)
            ->where('entidad_id', $incidente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // AUDITORÍA
        logAuditoria(
            'view',
            'bitacora_documentos',
            "Visualizó documentos del incidente ID {$incidente->id}",
            $incidente->establecimiento_id
        );

        return view('modulos.convivencia-escolar.documentos.index',
            compact('incidente', 'documentos'));
    }

    /**
     * Subir documento a incidente.
     */
    public function store(Request $request, BitacoraIncidente $incidente)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        if ($incidente->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $file = $request->file('archivo');

        // Guardado: /storage/documentos/bitacora/{incidente_id}/
        $ruta = $file->store("documentos/bitacora/{$incidente->id}", 'public');

        DocumentoAdjunto::create([
            'entidad_type'       => BitacoraIncidente::class,
            'entidad_id'         => $incidente->id,
            'nombre_archivo'     => $file->getClientOriginalName(),
            'ruta_archivo'       => "storage/" . $ruta,
            'subido_por'         => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
            'activo'             => 1,
        ]);

        // AUDITORÍA
        logAuditoria(
            'create',
            'bitacora_documentos',
            "Adjuntó documento al incidente ID {$incidente->id}",
            $incidente->establecimiento_id
        );

        return redirect()
            ->route('convivencia.bitacora.documentos.index', $incidente->id)
            ->with('success', 'Documento subido correctamente.');
    }

    /**
     * Invalidar documento.
     */
    public function disable($id)
    {
        $documento = DocumentoAdjunto::findOrFail($id);

        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $documento->update([
            'activo'         => 0,
            'invalidado_por' => Auth::user()->funcionario_id,
            'invalidado_en'  => now(),
        ]);

        // AUDITORÍA
        logAuditoria(
            'disable',
            'bitacora_documentos',
            "Invalidó documento ID {$documento->id}",
            $documento->establecimiento_id
        );

        return back()->with('success', 'Documento invalidado correctamente.');
    }

    /**
     * Habilitar documento.
     */
    public function enable($id)
    {
        $documento = DocumentoAdjunto::findOrFail($id);

        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $documento->update([
            'activo'         => 1,
            'invalidado_por' => null,
            'invalidado_en'  => null,
        ]);
        
        // AUDITORÍA
        logAuditoria(
            'enable',
            'bitacora_documentos',
            "Habilitó documento ID {$documento->id}",
            $documento->establecimiento_id
        );

        return back()->with('success', 'Documento habilitado nuevamente.');
    }
}
