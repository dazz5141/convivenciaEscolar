@extends('layouts.app')

@section('title', 'Roles del Sistema')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Roles del Sistema</h1>
        <p class="text-muted">Administración completa de perfiles del sistema</p>
    </div>

    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Rol
    </a>
</div>

<div class="card card-table mt-3">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($roles as $r)
                    <tr>
                        <td>{{ $r->nombre }}</td>

                        <td class="text-end">
                            <a href="{{ route('roles.edit', $r->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
