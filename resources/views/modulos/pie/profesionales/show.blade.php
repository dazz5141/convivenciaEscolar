@extends('layouts.app')

@section('title', 'Detalle del Profesional PIE')

@section('content')

<div class="page-header d-flex justify-content-between flex-wrap align-items-center mb-3">
    <div>
        <h1 class="page-title">Profesional PIE</h1>
        <p class="text-muted mb-0">Detalle completo del profesional del Programa de Integración Escolar</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('pie.profesionales.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- =============================
        COLUMNA IZQUIERDA
    ============================= --}}
    <div class="col-lg-8">

        {{-- DATOS DEL FUNCIONARIO --}}
        <div class="form-section">
            <h5 class="form-section-title">Datos del Funcionario</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre Completo:</div>
                <div class="detail-value">
                    {{ $profesional->funcionario->apellido_paterno }}
                    {{ $profesional->funcionario->apellido_materno }},
                    {{ $profesional->funcionario->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">
                    {{ $profesional->funcionario->cargo->nombre ?? 'Sin cargo asignado' }}
                </div>
            </div>
        </div>

        {{-- DATOS DEL PROFESIONAL PIE --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Información PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Tipo de Profesional PIE:</div>
                <div class="detail-value">
                    {{ $profesional->tipo->nombre ?? 'Sin tipo definido' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha de Registro:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($profesional->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- =============================
        COLUMNA DERECHA (RESUMEN)
    ============================= --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">
                <p class="mb-2">
                    <strong>Funcionario:</strong><br>
                    {{ $profesional->funcionario->apellido_paterno }}
                    {{ $profesional->funcionario->apellido_materno }},
                    {{ $profesional->funcionario->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Cargo:</strong><br>
                    {{ $profesional->funcionario->cargo->nombre ?? 'Sin cargo' }}
                </p>

                <p class="mb-2">
                    <strong>Tipo PIE:</strong><br>
                    {{ $profesional->tipo->nombre ?? '—' }}
                </p>

                <p class="mb-2">
                    <strong>Registrado el:</strong><br>
                    {{ \Carbon\Carbon::parse($profesional->created_at)->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
