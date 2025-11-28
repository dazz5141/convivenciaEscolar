@extends('layouts.app')

@section('title', 'Dashboard Administrador General')

@section('content')

<div class="page-header">
    <h1 class="page-title">Panel de Administración</h1>
    <p class="text-muted">Gestión global del sistema — vista exclusiva para Administrador General</p>
</div>

{{-- ============================================================
     ACCESOS DIRECTOS GRANDES (VERSIÓN C COMPLETA)
============================================================ --}}
<div class="row g-4 mb-4">

    {{-- Establecimientos --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('establecimientos.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-primary text-white">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Establecimientos</h5>
                        <p class="text-muted small m-0">Administración de colegios</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Usuarios --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('usuarios.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-success text-white">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Usuarios del Sistema</h5>
                        <p class="text-muted small m-0">Cuentas, roles y accesos</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Funcionarios --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('funcionarios.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-warning text-white">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Funcionarios</h5>
                        <p class="text-muted small m-0">Personal registrado</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Auditoría --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('auditoria.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-danger text-white">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Auditoría del Sistema</h5>
                        <p class="text-muted small m-0">Registros de actividad</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Módulo Convivencia --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('convivencia.bitacora.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-info text-white">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Convivencia Escolar</h5>
                        <p class="text-muted small m-0">Incidentes y seguimiento</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Ley Karin --}}
    <div class="col-12 col-md-6 col-xl-4">
        <a href="{{ route('leykarin.denuncias.index') }}" class="text-decoration-none">
            <div class="card shadow-sm quick-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="quick-icon bg-secondary text-white">
                        <i class="bi bi-exclamation-octagon"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">Ley Karin</h5>
                        <p class="text-muted small m-0">Conflictos y denuncias</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    .quick-card:hover {
        transform: scale(1.01);
        transition: 0.2s;
    }
    .quick-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }
</style>


{{-- ============================================================
     MÉTRICAS PRINCIPALES
============================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-primary"><i class="bi bi-building"></i></div>
                <div class="ms-3">
                    <div class="text-muted small">Establecimientos</div>
                    <h3 class="mb-0">{{ $totalEstablecimientos }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-success"><i class="bi bi-people"></i></div>
                <div class="ms-3">
                    <div class="text-muted small">Usuarios</div>
                    <h3 class="mb-0">{{ $totalUsuarios }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-warning"><i class="bi bi-person-workspace"></i></div>
                <div class="ms-3">
                    <div class="text-muted small">Funcionarios</div>
                    <h3 class="mb-0">{{ $totalFuncionarios }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-info"><i class="bi bi-mortarboard"></i></div>
                <div class="ms-3">
                    <div class="text-muted small">Alumnos</div>
                    <h3 class="mb-0">{{ $totalAlumnos }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     TABLA: ESTABLECIMIENTOS CON MÁS INCIDENTES
============================================================ --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Establecimientos con más incidentes (últimos 30 días)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Colegio</th>
                        <th class="text-end">Incidentes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incidentesPorColegio as $i => $c)
                        <tr>
                            <td>{{ $c->establecimiento->nombre }}</td>
                            <td class="text-end fw-semibold">{{ $c->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted p-3">Sin datos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ============================================================
     TABLA: ÚLTIMOS INCIDENTES GLOBALES
============================================================ --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Últimos incidentes registrados</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno(s)</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimosIncidentes as $it)
                        <tr>
                            <td>{{ $it->fecha->format('d-m-Y') }}</td>
                            <td>
                                @foreach($it->alumnos as $al)
                                    <span class="badge bg-secondary">{{ $al->nombre_completo }}</span>
                                @endforeach
                            </td>
                            <td>{{ $it->curso->nombre ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ $it->estado->nombre }}</span></td>
                            <td class="text-truncate" style="max-width: 400px;">{{ $it->descripcion }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted p-3">Sin registros recientes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
