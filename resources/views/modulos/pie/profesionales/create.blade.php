@extends('layouts.app')

@section('title', 'Nuevo Profesional PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Profesional PIE</h1>
    <p class="text-muted">Asignar un funcionario al Programa de Integración Escolar</p>
</div>

<form action="{{ route('pie.profesionales.store') }}" method="POST">
    @csrf

    <div class="form-section">
        <h5 class="form-section-title">Funcionario</h5>

        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="funcionario_id" id="inputFuncionarioSeleccionado" required>

        <p class="fw-bold" id="textoFuncionarioSeleccionado">Ningún funcionario seleccionado.</p>
    </div>

    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo Profesional PIE</h5>

        <select name="tipo_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="d-flex gap-2 flex-wrap mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Profesional
        </button>

        <a href="{{ route('pie.profesionales.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

{{-- MODAL --}}
@include('modulos.pie.partials.modal-buscar-funcionario')

@endsection
