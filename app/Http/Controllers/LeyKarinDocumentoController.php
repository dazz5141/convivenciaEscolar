<?php

namespace App\Http\Controllers;

use App\Models\DenunciaLeyKarin;
use App\Models\DocumentoAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Solo documentos activos
        $documentos = DocumentoAdjunto::where('activo', 1)
            ->where('entidad_type', DenunciaLeyKarin::class)
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
            'entidad_type'       => DenunciaLeyKarin::class,
            'entidad_id'         => $denuncia->id,
            'nombre_archivo'     => $file->getClientOriginalName(),
            'ruta_archivo'       => "storage/" . $ruta,
            'subido_por'         => Auth::user()->funcionario_id,
            'establecimiento_id' => session('establecimiento_id'),
            'activo'             => 1,
        ]);

        return redirect()
            ->route('leykarin.documentos.index', $denuncia)
            ->with('success', 'Documento adjuntado correctamente.');
    }


    /**
     * DESHABILITAR DOCUMENTO (invalidar)
     */
    public function disable($id)
    {
        $documento = DocumentoAdjunto::findOrFail($id);

        // Seguridad multicolegio
        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $documento->update([
            'activo'         => 0,
            'invalidado_por' => Auth::user()->funcionario_id,
            'invalidado_en'  => now(),
        ]);

        return back()->with('success', 'Documento invalidado correctamente.');
    }


    /**
     * HABILITAR DOCUMENTO (opcional)
     */
    public function enable($id)
    {
        $documento = DocumentoAdjunto::findOrFail($id);

        // Seguridad multicolegio
        if ($documento->establecimiento_id != session('establecimiento_id')) {
            abort(403);
        }

        $documento->update([
            'activo'         => 1,
            'invalidado_por' => null,
            'invalidado_en'  => null,
        ]);

        return back()->with('success', 'Documento habilitado nuevamente.');
    }
}
