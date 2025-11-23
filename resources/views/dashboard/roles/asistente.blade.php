@extends('layouts.app')

@section('title', 'Dashboard Asistente')

@section('content')

<div class="page-header">
    <h1 class="page-title">Asistente Administrativo</h1>
    <p class="text-muted">Panel de apoyo administrativo</p>
</div>

<div class="row g-4">

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Gestión de Estudiantes</h5>
                <a href="{{ route('alumnos.index') }}" class="btn btn-primary btn-sm">Ver alumnos</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Apoderados</h5>
                <a href="{{ route('apoderados.index') }}" class="btn btn-secondary btn-sm">Administrar</a>
            </div>
        </div>
    </div>

</div>

@endsection
