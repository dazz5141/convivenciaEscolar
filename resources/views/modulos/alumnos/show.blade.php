@extends('layouts.app')

@section('title', 'Ficha del Alumno')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">
            {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
        </h1>
        <p class="text-muted">RUN: {{ $alumno->run }}</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">

    <div class="col-lg-8">

        {{-- Datos personales --}}
        <div class="form-section">
            <h5 class="form-section-title">Datos Personales</h5>

            <div class="detail-item">
                <div class="detail-label">RUT:</div>
                <div class="detail-value">{{ $alumno->run }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha de nacimiento:</div>
                <div class="detail-value">
                    {{ $alumno->fecha_nacimiento ? $alumno->fecha_nacimiento->format('d/m/Y') : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Sexo:</div>
                <div class="detail-value">{{ $alumno->sexo->nombre ?? '—' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Correo:</div>
                <div class="detail-value">{{ $alumno->email ?? '—' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Teléfono:</div>
                <div class="detail-value">{{ $alumno->telefono ?? '—' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Dirección:</div>
                <div class="detail-value">{{ $alumno->direccion ?? '—' }}</div>
            </div>
        </div>

        {{-- Académico --}}
        <div class="form-section">
            <h5 class="form-section-title">Información Académica</h5>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">{{ $alumno->curso->nombre }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha de ingreso:</div>
                <div class="detail-value">
                    {{ $alumno->fecha_ingreso ? $alumno->fecha_ingreso->format('d/m/Y') : '—' }}
                </div>
            </div>

        </div>

        {{-- Relación Apoderados --}}
        <div class="form-section">
            <h5 class="form-section-title">Apoderados Asociados</h5>

            @forelse($alumno->apoderados as $ap)
                <div class="detail-item">
                    <div class="detail-label">{{ $ap->pivot->tipo }}:</div>
                    <div class="detail-value">
                        {{ $ap->nombre }} {{ $ap->apellido_paterno }} {{ $ap->apellido_materno }}
                        — <span class="text-muted">{{ $ap->telefono }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted">Sin apoderados registrados.</p>
            @endforelse
        </div>

    </div>

    {{-- Sidebar derecha --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estado</h5>
            </div>
            <div class="card-body">
                <span class="badge bg-{{ $alumno->activo ? 'success' : 'secondary' }}">
                    {{ $alumno->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>

    </div>

</div>

@endsection
