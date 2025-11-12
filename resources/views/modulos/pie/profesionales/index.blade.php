@extends('layouts.app')

@section('title', 'Profesionales PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Profesionales PIE</h1>
        <p class="text-muted">Listado de profesionales asignados al Programa de Integración Escolar</p>
    </div>

    <a href="{{ route('pie.profesionales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Profesional PIE
    </a>
</div>

<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Funcionario</th>
                        <th>Tipo Profesional</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($profesionales as $p)
                        <tr>
                            <td>
                                <strong>{{ $p->funcionario->apellido_paterno }} {{ $p->funcionario->apellido_materno }}, {{ $p->funcionario->nombre }}</strong>
                                <br>
                                <span class="text-muted small">{{ $p->funcionario->cargo->nombre ?? 'Sin cargo' }}</span>
                            </td>

                            <td>{{ $p->tipo->nombre }}</td>

                            <td>
                                <a href="{{ route('pie.profesionales.show', $p->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No hay profesionales PIE registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $profesionales->links() }}
    </div>
</div>

@endsection
