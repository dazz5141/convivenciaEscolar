@extends('layouts.app')

@section('title', 'Dashboard Inspector General')

@section('content')

<div class="page-header">
    <h1 class="page-title">Inspector General</h1>
    <p class="text-muted">Supervisión general del establecimiento</p>
</div>

<div class="row g-4">

    {{-- Accidentes --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Accidentes Escolares</h5>
                <a href="{{ route('inspectoria.accidentes.index') }}" class="btn btn-primary btn-sm">Ver módulo</a>
            </div>
        </div>
    </div>

    {{-- Novedades --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Novedades de Inspectoría</h5>
                <a href="{{ route('inspectoria.novedades.index') }}" class="btn btn-secondary btn-sm">Revisar</a>
            </div>
        </div>
    </div>

</div>

@endsection
