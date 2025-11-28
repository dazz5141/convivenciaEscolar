@extends('layouts.app')

@section('title', 'Detalle del Conflicto con Apoderado')

@section('content')

{{-- =========================================================
      PERMISO: VER
=========================================================== --}}
@if(!canAccess('conflictos_apoderados','view'))
    <div class="alert alert-danger mb-4">
        <i class="bi bi-shield-lock-fill me-2"></i>
        No tienes permisos para ver el detalle de los conflictos entre apoderados.
    </div>
@endif


<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Conflicto #{{ $conflicto->id }}</h1>
        <p class="text-muted">Información completa del caso registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('leykarin.conflictos-apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        @if(canAccess('conflictos_apoderados','edit'))
            <a href="{{ route('leykarin.conflictos-apoderados.edit', $conflicto) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i> Editar
            </a>
        @endif

    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA — Información principal
    ============================================================ --}}
    <div class="col-lg-8">

        <div class="form-section">
            <h5 class="form-section-title">Información del Conflicto</h5>

            {{-- FECHA --}}
            <div class="detail-item">
                <div class="detail-label">Fecha del conflicto:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($conflicto->fecha)->format('d/m/Y') }}
                </div>
            </div>

            {{-- FUNCIONARIO --}}
            <div class="detail-item">
                <div class="detail-label">Funcionario:</div>
                <div class="detail-value">
                    {{ $conflicto->funcionario->nombre_completo ?? '—' }}
                </div>
            </div>

            {{-- APODERADO --}}
            <div class="detail-item">
                <div class="detail-label">Apoderado involucrado:</div>
                <div class="detail-value">

                    @if($conflicto->apoderado)
                        {{ $conflicto->apoderado->nombre_completo }}
                        <br>
                        <small class="text-muted">RUN: {{ $conflicto->apoderado->rut }}</small>
                    @else
                        {{ $conflicto->apoderado_nombre ?? '—' }}
                        <br>
                        <small class="text-muted">RUN: {{ $conflicto->apoderado_rut ?? '' }}</small>
                    @endif

                </div>
            </div>

            {{-- TIPO --}}
            <div class="detail-item">
                <div class="detail-label">Tipo de conflicto:</div>
                <div class="detail-value">
                    {{ $conflicto->tipo_conflicto ?? '—' }}
                </div>
            </div>

            {{-- LUGAR --}}
            <div class="detail-item">
                <div class="detail-label">Lugar del conflicto:</div>
                <div class="detail-value">
                    {{ $conflicto->lugar_conflicto ?? 'No especificado' }}
                </div>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="detail-item">
                <div class="detail-label">Descripción detallada:</div>
                <div class="detail-value">
                    {!! $conflicto->descripcion
                        ? nl2br(e($conflicto->descripcion))
                        : '<span class="text-muted">Sin información disponible.</span>' !!}
                </div>
            </div>

            {{-- ACCIÓN --}}
            <div class="detail-item">
                <div class="detail-label">Acción tomada:</div>
                <div class="detail-value">
                    {!! $conflicto->accion_tomada
                        ? nl2br(e($conflicto->accion_tomada))
                        : '<span class="text-muted">Sin acciones registradas.</span>' !!}
                </div>
            </div>

            {{-- ESTADO --}}
            <div class="detail-item">
                <div class="detail-label">Estado del caso:</div>
                <div class="detail-value">
                    @if($conflicto->estado)
                        <span class="badge bg-primary">{{ $conflicto->estado->nombre }}</span>
                    @else
                        <span class="badge bg-secondary">Sin estado</span>
                    @endif
                </div>
            </div>

            {{-- CONFIDENCIAL --}}
            <div class="detail-item">
                <div class="detail-label">Confidencial:</div>
                <div class="detail-value">
                    @if($conflicto->confidencial)
                        <span class="text-danger fw-bold">
                            <i class="bi bi-lock-fill me-1"></i> Sí
                        </span>
                    @else
                        <span class="text-muted">
                            <i class="bi bi-unlock me-1"></i> No
                        </span>
                    @endif
                </div>
            </div>

            {{-- DERIVADO --}}
            <div class="detail-item">
                <div class="detail-label">Derivado Ley Karin:</div>
                <div class="detail-value">
                    @if($conflicto->derivado_ley_karin)
                        <span class="badge bg-danger">Sí</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </div>
            </div>

            {{-- REGISTRADO POR --}}
            <div class="detail-item">
                <div class="detail-label">Registrado por:</div>
                <div class="detail-value">

                    @if($conflicto->registradoPor)
                        {{ $conflicto->registradoPor->nombre_completo }}
                        <br>
                        <small class="text-muted">{{ $conflicto->registradoPor->cargo->nombre ?? '' }}</small>
                    @else
                        <span class="text-muted">No disponible</span>
                    @endif

                </div>
            </div>

        </div>

    </div>

    {{-- ============================================================
         COLUMNA DERECHA — Resumen
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Funcionario:</strong><br>
                    {{ $conflicto->funcionario->nombre_completo ?? '—' }}
                </p>

                <p class="mb-2">
                    <strong>Apoderado:</strong><br>
                    @if($conflicto->apoderado)
                        {{ $conflicto->apoderado->nombre_completo }}
                    @else
                        {{ $conflicto->apoderado_nombre ?? '—' }}
                    @endif
                </p>

                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ $conflicto->tipo_conflicto ?? '—' }}
                </p>

                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    @if($conflicto->estado)
                        <span class="badge bg-primary">{{ $conflicto->estado->nombre }}</span>
                    @else
                        <span class="badge bg-secondary">Sin estado</span>
                    @endif
                </p>

                <p class="mb-2">
                    <strong>Confidencial:</strong><br>
                    @if($conflicto->confidencial)
                        <span class="text-danger">
                            <i class="bi bi-lock-fill me-1"></i> Sí
                        </span>
                    @else
                        <span class="text-muted">
                            <i class="bi bi-unlock me-1"></i> No
                        </span>
                    @endif
                </p>

                <p class="mb-2">
                    <strong>Registrado por:</strong><br>
                    {{ $conflicto->registradoPor->nombre_completo ?? 'No disponible' }}
                </p>

            </div>
        </div>

    </div>

</div>

@endsection
