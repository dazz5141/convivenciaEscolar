@extends('layouts.app')

@section('title', 'Dashboard Convivencia Escolar')

@section('content')

<div class="page-header">
    <h1 class="page-title">Convivencia Escolar</h1>
    <p class="text-muted">Gestión de incidentes y seguimiento emocional</p>
</div>

<div class="row g-4">

    {{-- Bitácora de incidentes --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Bitácora de Incidentes</h5>
                <a href="{{ route('convivencia.bitacora.index') }}" class="btn btn-primary btn-sm">Ingresar</a>
            </div>
        </div>
    </div>

    {{-- Seguimiento emocional --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Seguimiento Emocional</h5>
                <a href="{{ route('convivencia.seguimiento.index') }}" class="btn btn-secondary btn-sm">Gestionar</a>
            </div>
        </div>
    </div>

</div>

@endsection
