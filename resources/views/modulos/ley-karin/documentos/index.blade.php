@extends('layouts.app')

@section('title', 'Documentos Adjuntos – Denuncia Ley Karin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Documentos de la Denuncia</h1>
        <p class="text-muted">Gestione los documentos de respaldo asociados a la denuncia.</p>
    </div>

    <a href="{{ route('leykarin.denuncias.show', $denuncia) }}" 
       class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver a Detalle
    </a>
</div>

{{-- =========================================================
     FORMULARIO PARA SUBIR ARCHIVOS
========================================================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Subir nuevo documento</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('leykarin.documentos.store', $denuncia->id) }}"
              method="POST" 
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label class="form-label">Seleccione archivo</label>
                <input type="file"
                       name="archivo"
                       class="form-control @error('archivo') is-invalid @enderror"
                       accept="application/pdf,image/*"
                       required>

                @error('archivo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="text-muted">
                    Formatos permitidos: PDF, JPG, PNG (máx. 10 MB)
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-cloud-upload me-2"></i> Subir Documento
            </button>
        </form>
    </div>
</div>


{{-- =========================================================
     LISTADO DE DOCUMENTOS
========================================================= --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-folder2-open me-2"></i>Documentos adjuntos</h5>
    </div>

    <div class="card-body">

        @if($documentos->count() == 0)
            <p class="text-muted">No hay documentos adjuntos.</p>
        @else

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Subido por</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($documentos as $doc)
                    <tr>
                        <td>
                            <i class="bi bi-file-earmark-text me-2"></i>
                            {{ $doc->nombre_archivo }}
                        </td>

                        <td>
                            {{ $doc->funcionario->nombre_completo ?? '—' }}
                        </td>

                        <td>
                            {{ $doc->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="text-end">

                            {{-- Ver / Descargar --}}
                            <a href="{{ asset($doc->ruta_archivo) }}" 
                               target="_blank"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-box-arrow-down"></i>
                            </a>

                            {{-- Invalidar documento --}}
                            <button class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalInvalidarDocumento{{ $doc->id }}">
                                <i class="bi bi-slash-circle"></i>
                            </button>

                        </td>
                    </tr>

                    {{-- Modal invalidación --}}
                    <div class="modal fade" id="modalInvalidarDocumento{{ $doc->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Invalidar Documento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    ¿Está seguro que desea invalidar este documento?<br>
                                    <strong>{{ $doc->nombre_archivo }}</strong>
                                    <br><br>
                                    <small class="text-muted">
                                        El archivo no se eliminará del servidor, pero quedará marcado como inactivo.
                                    </small>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>

                                    <form action="{{ route('leykarin.documentos.disable', $doc->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-slash-circle me-2"></i> Invalidar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>

            </table>

        @endif

    </div>
</div>

@endsection
