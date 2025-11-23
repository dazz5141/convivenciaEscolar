@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')

<div class="page-header">
    <h1 class="page-title">Panel de Administración</h1>
    <p class="text-muted">Control total del sistema</p>
</div>

<div class="row g-4">

    {{-- Gestión de Establecimientos --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Establecimientos</h5>
                <p class="text-muted">Administrar colegios registrados.</p>
                <a href="{{ route('establecimientos.index') }}" class="btn btn-primary btn-sm">Administrar</a>
            </div>
        </div>
    </div>

    {{-- Usuarios --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Usuarios del Sistema</h5>
                <p class="text-muted">Gestión de cuentas y roles.</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">Ver usuarios</a>
            </div>
        </div>
    </div>

</div>

@endsection
