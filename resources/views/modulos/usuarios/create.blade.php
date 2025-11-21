@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Nuevo Usuario</h1>
        <p class="text-muted">Crear una nueva cuenta de acceso</p>
    </div>
</div>

<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         DATOS DE ACCESO
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Datos de Acceso</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Email institucional *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

            </div>

        </div>
    </div>

    {{-- =========================================================
         ASIGNACIÓN DEL USUARIO
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Asignación del Usuario</h5>

            <div class="row g-3">

                {{-- Rol --}}
                <div class="col-md-4">
                    <label class="form-label">Rol *</label>
                    <select name="rol_id" class="form-select" required>
                        <option value="">— Seleccione —</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Funcionario --}}
                <div class="col-md-4">
                    <label class="form-label">Funcionario asociado</label>

                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               id="textoFuncionarioSeleccionado"
                               placeholder="Seleccione un funcionario..."
                               readonly>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarFuncionario">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden" name="funcionario_id" id="inputFuncionarioSeleccionado">
                </div>

                {{-- Establecimiento --}}
                <div class="col-md-4">
                    <label class="form-label">Establecimiento</label>
                    <select name="establecimiento_id" class="form-select">
                        <option value="">— No asignado —</option>
                        @foreach($establecimientos as $est)
                            <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>
    </div>

    {{-- =========================================================
         DATOS PERSONALES
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Datos Personales (Autollenado si selecciona funcionario)</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" id="apellido_materno" class="form-control">
                </div>
            </div>

        </div>
    </div>

    {{-- BOTONES --}}
    <div class="d-flex gap-3">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar
        </button>

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

{{-- =========================================================
     MODAL BÚSQUEDA FUNCIONARIO
========================================================= --}}
@include('modulos.usuarios.modal-buscar-funcionario')

@endsection


{{-- =========================================================
     JS AUTORELLENAR DATOS DEL FUNCIONARIO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombreCompleto = e.target.dataset.nombre;

        // Guardar el ID del funcionario
        document.getElementById('inputFuncionarioSeleccionado').value = id;

        // Mostrar texto seleccionado
        document.getElementById('textoFuncionarioSeleccionado').value = nombreCompleto;

        // Procesar nombre y apellidos
        let partes = nombreCompleto.trim().split(" ");
        let nombre = partes.pop(); // último
        let apellidos = partes.join(" ").split(" ");

        document.getElementById('nombre').value = nombre;
        document.getElementById('apellido_paterno').value = apellidos[0] ?? '';
        document.getElementById('apellido_materno').value = apellidos[1] ?? '';

        // Cerrar modal
        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>
