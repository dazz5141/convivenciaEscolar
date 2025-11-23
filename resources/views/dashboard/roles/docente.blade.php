@extends('layouts.app')

@section('title', 'Dashboard Docente')

@section('content')

<div class="page-header">
    <h1 class="page-title">Bienvenido, {{ auth()->user()->nombre_completo }}</h1>
    <p class="text-muted">Panel de profesor</p>
</div>

<div class="row g-4">

    {{-- Mis cursos --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Mis Cursos</h5>
                <p class="text-muted">Cursos y estudiantes asignados.</p>
                <a href="{{ route('docente.cursos.index') }}" class="btn btn-primary btn-sm">Ver cursos</a>
            </div>
        </div>
    </div>

    {{-- Atrasos recientes --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Atrasos del día</h5>
                <a href="{{ route('inspectoria.asistencia.index') }}" class="btn btn-secondary btn-sm">Ver atrasos</a>
            </div>
        </div>
    </div>

</div>

@endsection
