@extends('layouts.app')

@section('title', 'Dashboard Inspector')

@section('content')

<div class="page-header">
    <h1 class="page-title">Inspectoría</h1>
    <p class="text-muted">Gestión diaria del establecimiento</p>
</div>

<div class="row g-4">

    {{-- Atrasos --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Atrasos</h5>
                <a href="{{ route('inspectoria.atrasos.index') }}" class="btn btn-primary btn-sm">Registrar / Ver</a>
            </div>
        </div>
    </div>

    {{-- Retiros --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Retiros Anticipados</h5>
                <a href="{{ route('inspectoria.retiros.index') }}" class="btn btn-secondary btn-sm">Administrar</a>
            </div>
        </div>
    </div>

</div>

@endsection
