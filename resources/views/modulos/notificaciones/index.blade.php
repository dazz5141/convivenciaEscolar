@extends('layouts.app')

@section('title', 'Mis Notificaciones')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Notificaciones</h1>
        <p class="text-muted">Listado de todas tus notificaciones del sistema</p>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver al Dashboard
    </a>
</div>

@include('components.alerts')

<div class="card shadow-sm">
    <div class="card-body">

        @if($notificaciones->count() == 0)
            <div class="alert alert-info">
                No tienes notificaciones registradas.
            </div>
        @else

            <div class="list-group">
                @foreach($notificaciones as $n)
                    <div class="list-group-item d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ ucfirst(str_replace('_', ' ', $n->tipo)) }}
                            </h6>

                            <div class="text-muted small">
                                {{ $n->mensaje }}
                            </div>

                            <div class="small text-secondary">
                                {{ $n->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        {{-- Estado --}}
                        @if(!$n->leida)
                            <span class="badge bg-danger">Nueva</span>
                        @else
                            <span class="badge bg-secondary">Leída</span>
                        @endif

                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notificaciones->links() }}
            </div>

        @endif

    </div>
</div>

@endsection
