@extends('layouts.app')

@section('title', 'Conflictos entre Funcionarios')

@section('content')

{{-- ============================================================
      PERMISO (Solo mostrar alerta, no cortar la vista)
============================================================= --}}
@if(!canAccess('conflictos_funcionarios','view'))
    <div class="alert alert-danger mb-4">
        <i class="bi bi-shield-lock-fill me-2"></i>
        No tienes permisos para ver los conflictos entre funcionarios.
    </div>
@endif


<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Conflictos entre Funcionarios</h1>
        <p class="text-muted">Registro oficial de situaciones conflictivas entre funcionarios</p>
    </div>

    {{-- BOTÓN CREAR --}}
    @if(canAccess('conflictos_funcionarios','create'))
        <a href="{{ route('leykarin.conflictos-funcionarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Registrar Conflicto
        </a>
    @endif
</div>


<div class="card card-table">

    {{-- ============================================================
            FILTROS
    ============================================================ --}}
    <div class="card-header">

        <form method="GET" action="{{ route('leykarin.conflictos-funcionarios.index') }}">
            <div class="row g-3">

                {{-- Buscar funcionario --}}
                <div class="col-12 col-md-5">

                    <div class="input-group">
                        <input type="text"
                               id="inputFuncionarioSeleccionado"
                               class="form-control"
                               value="{{ $funcionarioSeleccionado->nombre_completo ?? '' }}"
                               placeholder="Seleccione un funcionario..."
                               readonly>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarFuncionario">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden" name="funcionario_id" id="funcionario_id" value="{{ request('funcionario_id') }}">
                </div>

                @include('modulos.ley-karin.partials.modal-buscar-funcionario')

                {{-- Tipo --}}
                <div class="col-12 col-md-3">
                    <input type="text"
                           name="tipo_conflicto"
                           class="form-control"
                           placeholder="Tipo de conflicto"
                           value="{{ request('tipo_conflicto') }}">
                </div>

                {{-- Fecha --}}
                <div class="col-12 col-md-2">
                    <input type="date"
                           name="fecha"
                           class="form-control"
                           value="{{ request('fecha') }}">
                </div>

                {{-- Botón --}}
                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i> Filtrar
                    </button>
                </div>

            </div>
        </form>

    </div>


    {{-- ============================================================
            TABLA
    ============================================================ --}}
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
                            <th>Funcionarios</th>
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

                            {{-- Fecha --}}
                            <td>{{ date('d/m/Y', strtotime($c->fecha)) }}</td>

                            {{-- Funcionarios --}}
                            <td>
                                {{ $c->funcionario1->nombre_completo ?? '—' }}
                                <br>
                                <small class="text-muted">vs {{ $c->funcionario2->nombre_completo ?? '—' }}</small>
                            </td>

                            {{-- Tipo --}}
                            <td>{{ $c->tipo_conflicto ?? '—' }}</td>

                            {{-- Estado --}}
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

                            {{-- Registrado por --}}
                            <td>
                                {{ $c->registradoPor->funcionario->nombre_completo ?? '—' }}
                            </td>

                            {{-- Confidencial --}}
                            <td>
                                @if($c->confidencial)
                                    <i class="bi bi-lock-fill text-danger" title="Confidencial"></i>
                                @else
                                    <i class="bi bi-unlock text-muted"></i>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="table-actions">

                                {{-- Ver --}}
                                @if(canAccess('conflictos_funcionarios','view'))
                                    <a href="{{ route('leykarin.conflictos-funcionarios.show', $c) }}"
                                       class="btn btn-sm btn-info"
                                       title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif

                                {{-- Editar --}}
                                @if(canAccess('conflictos_funcionarios','edit'))
                                    <a href="{{ route('leykarin.conflictos-funcionarios.edit', $c) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3">
                {{ $conflictos->links() }}
            </div>

        @endif

    </div>

</div>

@endsection


{{-- ============================================================
      JS MODAL SELECCIÓN FUNCIONARIO
============================================================ --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('funcionario_id').value = id;
        document.getElementById('inputFuncionarioSeleccionado').value = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>
