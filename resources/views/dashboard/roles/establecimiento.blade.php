@extends('layouts.app')

@section('title', 'Dashboard del Establecimiento')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard del Establecimiento</h1>
    <p class="text-muted">Resumen de los últimos 30 días — colegio actual</p>
</div>

{{-- ========================
      TARJETAS PRINCIPALES
========================= --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Incidentes (hoy)</div>
                        <h3 class="mb-0">{{ $incidentesHoy }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Alumnos activos</div>
                        <h3 class="mb-0">{{ $alumnosActivos }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Citaciones pendientes</div>
                        <h3 class="mb-0">{{ $citacionesPendientes }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Incidentes (mes)</div>
                        <h3 class="mb-0">{{ $totalIncidentesMes }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ========================
      INCIDENTES POR MES + DISTRIBUCIÓN
========================= --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Incidentes por estado (30 días)</h5>
            </div>
            <div class="card-body">
                @if($incidentesPorEstado->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incidentesPorEstado as $row)
                                    <tr>
                                        <td>{{ $row->estado->nombre ?? '—' }}</td>
                                        <td class="text-end fw-semibold">{{ $row->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Sin datos en el período.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Seguimientos por nivel (30 días)</h5>
            </div>
            <div class="card-body">
                @if($seguimientosPorNivel->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nivel</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seguimientosPorNivel as $row)
                                    <tr>
                                        <td>{{ $row->nivel->nombre ?? '—' }}</td>
                                        <td class="text-end fw-semibold">{{ $row->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Sin datos en el período.</p>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- ========================
      TOP CURSOS
========================= --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Top 5 cursos con más incidentes (30 días)</h5>
    </div>
    <div class="card-body">
        @if($topCursos->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Curso</th>
                            <th class="text-end">Incidentes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCursos as $i => $c)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $c->curso->nombre ?? '—' }}</td>
                                <td class="text-end fw-semibold">{{ $c->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No hay cursos con incidentes en el período.</p>
        @endif
    </div>
</div>


{{-- ========================
      ÚLTIMOS INCIDENTES
========================= --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimos incidentes</h5>
        <a href="{{ route('bitacora.index') }}" class="btn btn-sm btn-primary">Ver bitácora</a>
    </div>
    <div class="card-body p-0">
        @if($ultimosIncidentes->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosIncidentes as $it)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($it->fecha)->format('d-m-Y') }}</td>
                                <td>{{ $it->alumno->nombre_completo ?? '—' }}</td>
                                <td>{{ $it->curso->nombre ?? '—' }}</td>
                                <td><span class="badge bg-secondary">{{ $it->estado->nombre ?? '—' }}</span></td>
                                <td class="text-truncate" style="max-width: 400px;">{{ $it->descripcion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted m-3">Sin registros recientes.</p>
        @endif
    </div>
</div>

@endsection
