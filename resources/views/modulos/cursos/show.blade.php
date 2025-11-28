@extends('layouts.app')

@section('title', 'Detalle del Curso')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">{{ $curso->nivel }} {{ $curso->letra }} - {{ $curso->anio }}</h1>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- ============================================
             BOTÓN EDITAR (SOLO SI TIENE PERMISO)
        ============================================ --}}
        @if(canAccess('cursos', 'edit'))
        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        @endif

        {{-- BOTÓN VOLVER (SIEMPRE VISIBLE) --}}
        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Datos del Curso</h5>

    <div class="detail-item">
        <div class="detail-label">Año:</div>
        <div class="detail-value">{{ $curso->anio }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Nivel:</div>
        <div class="detail-value">{{ $curso->nivel }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Letra:</div>
        <div class="detail-value">{{ $curso->letra }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Estado:</div>
        <div class="detail-value">
            <span class="badge bg-{{ $curso->activo ? 'success' : 'secondary' }}">
                {{ $curso->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>
</div>

<div class="form-section mt-4">
    <h5 class="form-section-title">Alumnos</h5>

    @if($curso->alumnos->count() == 0)
        <p class="text-muted">No hay alumnos asignados.</p>
    @else
        <ul class="list-group">
            @foreach($curso->alumnos as $al)
                <li class="list-group-item">
                    {{ $al->nombre }} {{ $al->apellido_paterno }} {{ $al->apellido_materno }}
                </li>
            @endforeach
        </ul>
    @endif
</div>

@endsection
