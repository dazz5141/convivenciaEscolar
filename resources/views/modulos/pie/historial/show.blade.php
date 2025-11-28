@extends('layouts.app')

@section('title', $titulo)

@section('content')

{{-- ============================================================
     PERMISO: VER
============================================================ --}}
@if(!canAccess('pie','view'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No tienes permisos para ver el detalle del historial PIE.
    </div>
    @return
@endif


<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">{{ $titulo }}</h1>
        <p class="text-muted">Revisión detallada del registro.</p>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>


{{-- ============================
       DATOS DEL ESTUDIANTE
============================= --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Estudiante Asociado</h5>
    </div>
    <div class="card-body">

        <strong>Nombre:</strong><br>
        {{ $data->estudiante->alumno->apellido_paterno }}
        {{ $data->estudiante->alumno->apellido_materno }},
        {{ $data->estudiante->alumno->nombre }}

        <hr>

        <strong>Curso:</strong><br>
        {{ $data->estudiante->alumno->curso->nombre ?? 'Sin curso' }}

    </div>
</div>



{{-- ============================
      DETALLE SEGÚN TIPO
============================= --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Información del Registro</h5>
    </div>

    <div class="card-body">

        {{-- ============================================
             INTERVENCIÓN
        ============================================= --}}
        @if($tipo === 'intervencion')

            <strong>Fecha:</strong><br>
            {{ \Carbon\Carbon::parse($data->fecha)->format('d/m/Y') }}
            <hr>

            <strong>Tipo de Intervención:</strong><br>
            {{ $data->tipo->nombre ?? 'Sin tipo' }}
            <hr>

            <strong>Profesional:</strong><br>
            {{ $data->profesional->nombre_completo ?? 'Sin profesional' }}
            <hr>

            <strong>Detalle:</strong><br>
            {!! nl2br(e($data->detalle)) !!}


        {{-- ============================================
             INFORME
        ============================================= --}}
        @elseif($tipo === 'informe')

            <strong>Fecha:</strong><br>
            {{ \Carbon\Carbon::parse($data->fecha)->format('d/m/Y') }}
            <hr>

            <strong>Tipo de Informe:</strong><br>
            {{ $data->tipo }}
            <hr>

            <strong>Contenido:</strong><br>
            {!! nl2br(e($data->contenido)) !!}


        {{-- ============================================
             PLAN INDIVIDUAL
        ============================================= --}}
        @elseif($tipo === 'plan')

            <strong>Fecha de Inicio:</strong><br>
            {{ \Carbon\Carbon::parse($data->fecha_inicio)->format('d/m/Y') }}
            <hr>

            <strong>Fecha de Término:</strong><br>
            {{ $data->fecha_termino ? \Carbon\Carbon::parse($data->fecha_termino)->format('d/m/Y') : '—' }}
            <hr>

            <strong>Objetivos:</strong><br>
            {!! nl2br(e($data->objetivos)) !!}
            <hr>

            <strong>Evaluación:</strong><br>
            {!! nl2br(e($data->evaluacion ?? '—')) !!}


        {{-- ============================================
             DERIVACIÓN
        ============================================= --}}
        @elseif($tipo === 'derivacion')

            <strong>Fecha:</strong><br>
            {{ \Carbon\Carbon::parse($data->fecha)->format('d/m/Y') }}
            <hr>

            <strong>Destino:</strong><br>
            {{ $data->destino }}
            <hr>

            <strong>Motivo:</strong><br>
            {!! nl2br(e($data->motivo)) !!}
            <hr>

            <strong>Estado:</strong><br>
            {{ $data->estado ?? '—' }}

        @endif

    </div>
</div>

@endsection
