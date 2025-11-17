@extends('layouts.app')

@section('title', 'Detalle de Denuncia Ley Karin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Denuncia #{{ $denuncia->id }}</h1>
        <p class="text-muted">Registro formal conforme a Ley Karin</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('leykarin.denuncias.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        <a href="{{ route('leykarin.denuncias.edit', $denuncia) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        {{-- Adjuntar documentos --}}
        <a href="{{ route('documentos.index', $denuncia) }}" class="btn btn-info">
            <i class="bi bi-paperclip me-2"></i> Documentos Adjuntos
        </a>

    </div>
</div>


<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA – INFORMACIÓN DETALLADA
    ============================================================ --}}
    <div class="col-lg-8">

        <div class="form-section">

            <h5 class="form-section-title">Datos del Denunciante</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre completo:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciante_nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">RUN:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciante_rut ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo / Rol:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciante_cargo ?? '—' }}
                </div>
            </div>

            <h5 class="form-section-title mt-4">Datos del Denunciado</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre completo:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciado_nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">RUN:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciado_rut ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo / Rol:</div>
                <div class="detail-value">
                    {{ $denuncia->denunciado_cargo ?? '—' }}
                </div>
            </div>

            <h5 class="form-section-title mt-4">Información de la Denuncia</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha de la denuncia:</div>
                <div class="detail-value">
                    {{ $denuncia->fecha_denuncia ? \Carbon\Carbon::parse($denuncia->fecha_denuncia)->format('d/m/Y') : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de denuncia:</div>
                <div class="detail-value">
                    {{ $denuncia->tipo?->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item mb-4">
                <div class="detail-label">Descripción detallada:</div>
                <div class="detail-value">
                    {!! $denuncia->descripcion 
                        ? nl2br(e($denuncia->descripcion))
                        : '<span class="text-muted">Sin información disponible.</span>' !!}
                </div>
            </div>


            <h5 class="form-section-title mt-4">Información Legal</h5>

            <div class="detail-item">
                <div class="detail-label">Confidencialidad:</div>
                <div class="detail-value">
                    @if($denuncia->confidencial)
                        <span class="text-danger fw-bold"><i class="bi bi-lock-fill me-1"></i> Confidencial</span>
                    @else
                        <span class="text-muted"><i class="bi bi-unlock me-1"></i> No confidencial</span>
                    @endif
                </div>
            </div>

            <div class="detail-item mb-4">
                <div class="detail-label">Derivada desde conflicto previo:</div>
                <div class="detail-value">
                    @if($denuncia->conflictable)
                        <span class="badge bg-primary">Sí</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </div>
            </div>


            <h5 class="form-section-title mt-4">Documentos Adjuntos</h5>

            @if($denuncia->documentos->count() == 0)
                <p class="text-muted">No hay documentos adjuntos.</p>
            @else
                <ul class="list-group">
                    @foreach ($denuncia->documentos as $doc)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-file-earmark-text me-2"></i>
                                {{ $doc->nombre_archivo }}
                            </span>
                            <a href="{{ asset($doc->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Ver / Descargar
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>

    </div>


    {{-- ============================================================
         COLUMNA DERECHA – RESUMEN
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-primary">
                        {{ $denuncia->estado ?? 'Pendiente' }}
                    </span>
                </p>

                <p class="mb-2">
                    <strong>Registrado por:</strong><br>
                    {{ $denuncia->registradoPor->nombre_completo ?? '—' }}<br>
                    <span class="text-muted">
                        {{ $denuncia->registradoPor->rol->nombre ?? '' }}
                    </span>
                </p>

                <p class="mb-2">
                    <strong>Fecha de registro:</strong><br>
                    {{ $denuncia->created_at->format('d/m/Y H:i') }}
                </p>

                @if($denuncia->conflictable)
                    <p class="mb-2">
                        <strong>Origen:</strong><br>
                        <a href="{{ $denuncia->conflictable instanceof \App\Models\ConflictoFuncionario
                                    ? route('leykarin.conflictos-funcionarios.show', $denuncia->conflictable_id)
                                    : route('leykarin.conflictos-apoderados.show', $denuncia->conflictable_id) }}"
                           class="text-primary">
                           Ver conflicto asociado
                        </a>
                    </p>
                @endif

            </div>
        </div>

    </div>

</div>

@endsection
