@extends('layouts.app')

@section('title', 'Detalle del Retiro Anticipado')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Retiro #{{ $retiro->id }}</h1>
        <p class="text-muted">Información completa del registro</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('inspectoria.retiros.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        <a href="{{ route('inspectoria.retiros.edit', $retiro) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN DEL RETIRO --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Retiro</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($retiro->fecha)->format('d/m/Y') }}
                    <span class="text-muted">{{ \Carbon\Carbon::parse($retiro->hora)->format('H:i') }}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Motivo:</div>
                <div class="detail-value">{{ $retiro->motivo ?? '—' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">
                    {!! $retiro->observaciones 
                        ? nl2br(e($retiro->observaciones)) 
                        : '<span class="text-muted">Sin información disponible.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Funcionario que entrega:</div>
                <div class="detail-value">
                    {{ $retiro->funcionarioEntrega->nombre_completo ?? 'No disponible' }}
                </div>
            </div>

        </div>


        {{-- PERSONA QUE RETIRA --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Persona que Retira</h5>

            @if($retiro->apoderado)
                {{-- Apoderado Registrado --}}
                <div class="detail-item">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">{{ $retiro->apoderado->nombre_completo }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Tipo:</div>
                    <div class="detail-value">Apoderado registrado</div>
                </div>

                @if($retiro->apoderado->run)
                <div class="detail-item">
                    <div class="detail-label">RUN:</div>
                    <div class="detail-value">{{ $retiro->apoderado->run }}</div>
                </div>
                @endif

            @else
                {{-- Retiro Manual --}}
                <div class="detail-item">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">{{ $retiro->nombre_retira ?? '—' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">RUN:</div>
                    <div class="detail-value">{{ $retiro->run_retira ?? '—' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Parentesco:</div>
                    <div class="detail-value">{{ $retiro->parentesco_retira ?? '—' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Teléfono:</div>
                    <div class="detail-value">{{ $retiro->telefono_retira ?? '—' }}</div>
                </div>
            @endif

        </div>


        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">{{ $retiro->alumno->nombre_completo }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $retiro->alumno->curso->nombre ?? '—' }}
                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         COLUMNA DERECHA (RESUMEN)
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                {{-- ALUMNO --}}
                <p class="mb-3">
                    <strong>Alumno:</strong><br>
                    {{ $retiro->alumno->nombre_completo }}<br>
                    <span class="text-muted">{{ $retiro->alumno->curso->nombre ?? '' }}</span>
                </p>

                {{-- FECHA --}}
                <p class="mb-3">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($retiro->fecha)->format('d/m/Y') }}<br>
                    <span class="text-muted">{{ \Carbon\Carbon::parse($retiro->hora)->format('H:i') }}</span>
                </p>

                {{-- RETIRADO POR --}}
                <p class="mb-3">
                    <strong>Retirado por:</strong><br>
                    @if($retiro->apoderado)
                        {{ $retiro->apoderado->nombre_completo }}
                    @else
                        {{ $retiro->nombre_retira ?? '—' }}
                    @endif
                </p>

                {{-- MOTIVO --}}
                <p class="mb-3">
                    <strong>Motivo:</strong><br>
                    {{ $retiro->motivo ?? '—' }}
                </p>

                {{-- FUNCIONARIO --}}
                <p class="mb-3">
                    <strong>Funcionario:</strong><br>
                    {{ $retiro->funcionarioEntrega->nombre_completo ?? '—' }}
                </p>

            </div>
        </div>

    </div>

</div>

@endsection
