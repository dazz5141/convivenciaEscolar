@extends('layouts.app')

@section('title', 'Detalle de la Novedad de Inspectoría')

@section('content')

{{-- =========================================================
     PERMISO PARA VER NOVEDADES
========================================================= --}}
@if(canAccess('novedades', 'view'))

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Novedad #{{ $novedad->id }}</h1>
        <p class="text-muted">Información completa del registro</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('inspectoria.novedades.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar (si tiene permiso) --}}
        @if(canAccess('novedades', 'edit'))
        <a href="{{ route('inspectoria.novedades.edit', $novedad) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        @endif

    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA (Información principal)
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Novedad</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($novedad->fecha)->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de novedad:</div>
                <div class="detail-value">
                    {{ $novedad->tipo->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">
                    {!! $novedad->descripcion 
                        ? nl2br(e($novedad->descripcion)) 
                        : '<span class="text-muted">Sin información disponible.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Registrado por:</div>
                <div class="detail-value">
                    {{ $novedad->funcionario->nombre_completo ?? 'No disponible' }}
                </div>
            </div>

        </div>

        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Involucrado</h5>

            @if($novedad->alumno)
                <div class="detail-item">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">
                        {{ $novedad->alumno->nombre_completo }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Curso:</div>
                    <div class="detail-value">
                        {{ $novedad->alumno->curso->nombre ?? '—' }}
                    </div>
                </div>
            @else
                <p class="text-muted m-0">No hay alumno asociado.</p>
            @endif
        </div>

    </div>

    {{-- ============================================================
         COLUMNA DERECHA (Resumen)
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                {{-- ALUMNO --}}
                <p class="mb-2">
                    <strong>Alumno:</strong><br>
                    @if($novedad->alumno)
                        {{ $novedad->alumno->nombre_completo }}<br>
                        <span class="text-muted">{{ $novedad->alumno->curso->nombre ?? '' }}</span>
                    @else
                        <span class="text-muted">No hay alumno registrado</span>
                    @endif
                </p>

                {{-- TIPO --}}
                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ $novedad->tipo->nombre }}
                </p>

                {{-- FECHA --}}
                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($novedad->fecha)->format('d/m/Y H:i') }}
                </p>

                {{-- FUNCIONARIO --}}
                <p class="mb-2">
                    <strong>Registrado por:</strong><br>
                    {{ $novedad->funcionario->nombre_completo ?? 'No disponible' }}
                </p>

            </div>
        </div>

    </div>
</div>

@else
{{-- =========================================================
     SIN PERMISOS
========================================================= --}}
<div class="alert alert-warning mt-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    No tienes permisos para ver esta novedad.
</div>

@endif

@endsection
