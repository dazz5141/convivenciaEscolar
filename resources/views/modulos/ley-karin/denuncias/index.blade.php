@extends('layouts.app')

@section('title', 'Denuncias Ley Karin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Denuncias Ley Karin</h1>
        <p class="text-muted">Registro oficial de denuncias ingresadas bajo la Ley Karin</p>
    </div>
    <div>
        <a href="{{ route('leykarin.denuncias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Denuncia
        </a>
    </div>
</div>

<div class="card card-table">

    <!-- FILTROS -->
    <div class="card-header">

        <form method="GET" action="{{ route('leykarin.denuncias.index') }}">
            <div class="row g-3">

                <!-- Buscar denunciante -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="input-group">
                        <input type="text"
                            id="inputDenuncianteSeleccionado"
                            class="form-control"
                            value="{{ $denuncianteSeleccionado->nombre_completo ?? '' }}"
                            placeholder="Buscar denunciante..."
                            readonly>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarFuncionario"
                                data-target="denunciante_id"
                                data-texto="inputDenuncianteSeleccionado">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden" name="denunciante_id" id="denunciante_id"
                        value="{{ request('denunciante_id') }}">
                </div>

                <!-- Buscar denunciado -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="input-group">
                        <input type="text"
                            id="inputDenunciadoSeleccionado"
                            class="form-control"
                            value="{{ $denunciadoSeleccionado->nombre_completo ?? '' }}"
                            placeholder="Buscar denunciado..."
                            readonly>

                        <button type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarFuncionario"
                                data-target="denunciado_id"
                                data-texto="inputDenunciadoSeleccionado">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden" name="denunciado_id" id="denunciado_id"
                        value="{{ request('denunciado_id') }}">
                </div>

                <!-- Tipo de acoso -->
                <div class="col-12 col-md-4 col-lg-2">
                    <input type="text"
                        name="tipo_acoso"
                        class="form-control"
                        placeholder="Tipo de acoso"
                        value="{{ request('tipo_acoso') }}">
                </div>

                <!-- Fecha -->
                <div class="col-12 col-md-4 col-lg-2">
                    <input type="date"
                        name="fecha"
                        class="form-control"
                        value="{{ request('fecha') }}">
                </div>

                <!-- Botón Filtrar -->
                <div class="col-12 col-md-4 col-lg-2">
                    <button class="btn btn-secondary w-100 h-100">
                        <i class="bi bi-funnel me-2"></i>
                        Filtrar
                    </button>
                </div>

            </div>
        </form>

    </div>

    <!-- TABLA -->
    <div class="card-body">

        @if($denuncias->count() == 0)

            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay denuncias registradas</p>
            </div>

        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Victima</th>
                        <th>Denunciado</th>
                        <th>Tipo</th>
                        <th>Confidencial</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($denuncias as $d)
                    <tr>

                        <!-- FECHA -->
                        <td>{{ $d->created_at->format('d/m/Y') }}</td>

                        <!-- VÍCTIMA -->
                        <td>{{ $d->victima_nombre ?? '—' }}</td>

                        <!-- DENUNCIADO -->
                        <td>{{ $d->denunciado_nombre ?? '—' }}</td>

                        <!-- TIPO -->
                        <td>
                            @php
                                $tipos = [
                                    $d->tipo_acoso,
                                    $d->tipo_laboral,
                                    $d->tipo_violencia,
                                ];
                                $tipos = array_filter($tipos);
                            @endphp

                            {{ count($tipos) ? implode(', ', $tipos) : '—' }}
                        </td>

                        <!-- CONFIDENCIAL -->
                        <td>
                            @if($d->confidencial)
                                <i class="bi bi-lock-fill text-danger" title="Confidencial"></i>
                            @else
                                <i class="bi bi-unlock text-muted"></i>
                            @endif
                        </td>

                        <!-- ACCIONES -->
                        <td class="table-actions">

                            <a href="{{ route('leykarin.denuncias.show', $d->id) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('leykarin.denuncias.edit', $d->id) }}"
                               class="btn btn-sm btn-primary"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-3">
            {{ $denuncias->links() }}
        </div>

        @endif

    </div>
</div>

@endsection


{{-- =========================================================
     JS PARA SELECCIÓN EN EL MODAL
========================================================= --}}
<script>
let targetInput = null;
let targetTexto = null;

document.querySelectorAll('[data-bs-target="#modalBuscarFuncionario"]').forEach(btn => {
    btn.addEventListener('click', () => {
        targetInput = btn.dataset.target;
        targetTexto = btn.dataset.texto;
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById(targetInput).value = id;
        document.getElementById(targetTexto).value = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>

{{-- MODAL --}}
@include('modulos.ley-karin.partials.modal-buscar-funcionario')
