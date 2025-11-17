<?php

namespace App\Http\Controllers;

use App\Models\DenunciaLeyKarin;
use App\Models\DocumentoAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeyKarinDocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos de una denuncia.
     */
    public function index(DenunciaLeyKarin $denuncia)
    {
        // Seguridad multi-colegio
        if ($denuncia->establecimiento_id != session('establecimiento_id')) {
            abort(403, 'Acceso denegado.');
        }

        $documentos = DocumentoAdjunto::where('entidad', DenunciaLeyKarin::class)
            ->where('entidad_id', $denuncia->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modulos.ley-karin.documentos.index', compact('denuncia', 'documentos'));
    }


    /**
     * Subir documento a la denuncia.
     */
    public function store(Request $request, DenunciaLeyKarin $denuncia)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        // seguridad
        if ($denuncia->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $file = $request->file('archivo');

        // Guardado en /storage/app/public/documentos/leykarin/{id}/
        $ruta = $file->store("documentos/leykarin/{$denuncia->id}", 'public');

        DocumentoAdjunto::create([
            'entidad'            => DenunciaLeyKarin::class,
            'entidad_id'         => $denuncia->id,
            'nombre_archivo'     => $file->getClientOriginalName(),
            'ruta_archivo'       => "storage/" . $ruta,
            'subido_por'         => Auth::user()->funcionario_id, 
            'establecimiento_id' => session('establecimiento_id'),
        ]);

        return redirect()
            ->route('leykarin.denuncias.documentos.index', $denuncia)
            ->with('success', 'Documento adjuntado correctamente.');
    }


    /**
     * Eliminar documento.
     */
    public function destroy(DocumentoAdjunto $documento)
    {
        // Seguridad
        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        // Borrar archivo físico
        if ($documento->ruta_archivo && file_exists(public_path($documento->ruta_archivo))) {
            unlink(public_path($documento->ruta_archivo));
        }

        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}
