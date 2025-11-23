@extends('layouts.app')

@section('title', 'Funcionarios')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Funcionarios</h1>
        <p class="text-muted">Gestión completa de funcionarios del establecimiento</p>
    </div>
    <div>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Funcionario
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-12 col-md-6">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre, apellido o RUN...">
            </div>

            <div class="col-12 col-md-4">
                <select name="estado" class="form-select">
                    <option value="">— Estado —</option>
                    <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">

        @if($funcionarios->count() == 0)
            <div class="alert alert-info">
                No se encontraron funcionarios registrados.
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>RUN</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Tipo Contrato</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($funcionarios as $f)
                    <tr>
                        <td>{{ $f->run }}</td>

                        <td>
                            {{ $f->nombre }} {{ $f->apellido_paterno }} {{ $f->apellido_materno }}
                        </td>

                        <td>{{ $f->cargo->nombre }}</td>

                        <td>{{ $f->tipoContrato->nombre }}</td>

                        <td>
                            @if($f->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td class="text-end">

                            {{-- Crear usuario solo si NO tiene --}}
                            @if(!$f->usuario)
                                <button class="btn btn-sm btn-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#crearUsuarioModal{{ $f->id }}">
                                    <i class="bi bi-person-plus"></i>
                                </button>
                            @endif

                            <a href="{{ route('funcionarios.show', $f->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('funcionarios.edit', $f->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($f->activo)
                                <form action="{{ route('funcionarios.disable', $f->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-warning" title="Deshabilitar">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('funcionarios.enable', $f->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-success" title="Habilitar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $funcionarios->links() }}

        @endif
    </div>
</div>
    {{-- ============================
        MODALES PARA CREAR USUARIO
    ============================ --}}
    @foreach($funcionarios as $f)
        <div class="modal fade" id="crearUsuarioModal{{ $f->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Crear Usuario — {{ $f->nombre }} {{ $f->apellido_paterno }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <div class="modal-body">

                            {{-- ID del funcionario --}}
                            <input type="hidden" name="funcionario_id" value="{{ $f->id }}">

                            <div class="mb-3">
                                <label class="form-label">Correo (opcional)</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="correo@colegio.cl">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña *</label>
                                <input type="text" name="password" class="form-control" required
                                    placeholder="Ingrese contraseña">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rol del Usuario *</label>
                                <select name="rol_id" class="form-select" required>
                                    <option value="">— Seleccione —</option>

                                    @foreach(\App\Models\Rol::orderBy('id')->get() as $r)

                                        {{-- Admin General --}}
                                        @if(auth()->user()->rol_id == 1)
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>

                                        {{-- Admin del Colegio --}}
                                        @elseif(auth()->user()->rol_id == 2 && $r->id != 1)
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>

                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> Crear Usuario
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach

@endsection
