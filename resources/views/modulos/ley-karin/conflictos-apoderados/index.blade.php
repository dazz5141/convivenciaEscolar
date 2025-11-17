@extends('layouts.app')

@section('title', 'Conflictos entre Apoderados')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Conflictos entre Apoderados</h1>
        <p class="text-muted">Registro oficial de situaciones entre apoderados y funcionarios</p>
    </div>
    <div>
        <a href="{{ route('leykarin.conflictos-apoderados.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Conflicto
        </a>
    </div>
</div>

<div class="card card-table">

    <!-- ========================= -->
    <!--          FILTROS          -->
    <!-- ========================= -->
    <div class="card-header">
        <form method="GET" action="{{ route('leykarin.conflictos-apoderados.index') }}">
            <div class="row g-3">

                <!-- Buscar apoderado -->
                <div class="col-12 col-md-5">

                    <div class="input-group">
                        <input type="text"
                               id="inputApoderadoSeleccionado"
                               class="form-control"
                               value="{{ $apoderadoSeleccionado->nombre_completo ?? $apoderadoSeleccionado->apoderado_nombre ?? '' }}"
                               placeholder="Seleccione un apoderado..."
                               readonly>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarApoderado">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden" name="apoderado_id" id="apoderado_id" value="{{ request('apoderado_id') }}">
                </div>

                {{-- Modal buscar apoderado --}}
                @include('modulos.ley-karin.partials.modal-buscar-apoderado')

                <!-- Tipo de conflicto -->
                <div class="col-12 col-md-3">
                    <input type="text"
                           name="tipo_conflicto"
                           class="form-control"
                           placeholder="Tipo de conflicto"
                           value="{{ request('tipo_conflicto') }}">
                </div>

                <!-- Fecha -->
                <div class="col-12 col-md-2">
                    <input type="date"
                           name="fecha"
                           value="{{ request('fecha') }}"
                           class="form-control">
                </div>

                <!-- Botón filtrar -->
                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>
                        Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- ========================= -->
    <!--           TABLA           -->
    <!-- ========================= -->
    <div class="card-body">

        @if($conflictos->count() == 0)

            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay conflictos registrados</p>
            </div>

        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Apoderado</th>
                        <th>Funcionario</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Registrado por</th>
                        <th>Confidencial</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($conflictos as $c)
                    <tr>

                        <!-- FECHA -->
                        <td>{{ date('d/m/Y', strtotime($c->fecha)) }}</td>

                        <!-- APODERADO -->
                        <td>
                            @if($c->apoderado_id)
                                {{-- Apoderado interno --}}
                                {{ $c->apoderado->nombre_completo }}
                                <br>
                                <small class="text-muted">{{ $c->apoderado->run }}</small>
                            @else
                                {{-- Apoderado externo --}}
                                {{ $c->apoderado_nombre ?? '—' }}
                                <br>
                                <small class="text-muted">{{ $c->apoderado_rut ?? '' }}</small>
                            @endif
                        </td>

                        <!-- FUNCIONARIO -->
                        <td>
                            {{ $c->funcionario->nombre_completo ?? '—' }}
                        </td>

                        <!-- TIPO -->
                        <td>{{ $c->tipo_conflicto ?? '—' }}</td>

                        <!-- ESTADO -->
                        <td>
                            @if($c->estado)
                                <span class="badge bg-primary">{{ $c->estado->nombre }}</span>
                            @else
                                <span class="badge bg-secondary">Sin estado</span>
                            @endif

                            @if($c->derivado_ley_karin)
                                <span class="badge bg-danger ms-1">Ley Karin</span>
                            @endif
                        </td>

                        <!-- REGISTRADO POR -->
                        <td>
                            {{ $c->registradoPor->nombre_completo ?? '—' }}
                        </td>

                        <!-- CONFIDENCIAL -->
                        <td>
                            @if($c->confidencial)
                                <i class="bi bi-lock-fill text-danger"></i>
                            @else
                                <i class="bi bi-unlock text-muted"></i>
                            @endif
                        </td>

                        <!-- ACCIONES -->
                        <td class="table-actions">
                            <a href="{{ route('leykarin.conflictos-apoderados.show', $c) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('leykarin.conflictos-apoderados.edit', $c) }}"
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
            {{ $conflictos->links() }}
        </div>

        @endif

    </div>
</div>

@endsection


{{-- ===========================================
      JS SELECCIÓN DE APODERADO
============================================== --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('apoderado_id').value = id;
        document.getElementById('inputApoderadoSeleccionado').value = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>
