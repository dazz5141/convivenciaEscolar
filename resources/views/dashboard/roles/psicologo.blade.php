@extends('layouts.app')

@section('title', 'Dashboard Psicólogo / PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Profesional PIE / Psicólogo</h1>
    <p class="text-muted">Intervenciones y planes de apoyo</p>
</div>

<div class="row g-4">

    {{-- Intervenciones --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Intervenciones PIE</h5>
                <a href="{{ route('pie.intervenciones.index') }}" class="btn btn-primary btn-sm">Ver intervenciones</a>
            </div>
        </div>
    </div>

    {{-- Planes PIE --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Planes Individuales</h5>
                <a href="{{ route('pie.planes.index') }}" class="btn btn-secondary btn-sm">Administrar</a>
            </div>
        </div>
    </div>

</div>

@endsection
