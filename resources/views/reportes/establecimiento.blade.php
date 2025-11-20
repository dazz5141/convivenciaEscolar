@extends('layouts.app')

@section('title', 'Reporte por Establecimiento')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Reporte por Establecimiento</h1>
        @if($establecimiento)
            <p class="text-muted mb-0">
                {{ $establecimiento->nombre }} 
                @if($establecimiento->RBD)
                    (RBD: {{ $establecimiento->RBD }})
                @endif
                @if($establecimiento->comuna)
                    – {{ $establecimiento->comuna->nombre }}
                @endif
            </p>
        @else
            <p class="text-muted mb-0">
                Selecciona un establecimiento para ver sus indicadores.
            </p>
        @endif
    </div>

    {{-- Selector solo útil para admin general --}}
    @if(auth()->check() && auth()->user()->rol_id == 1)
        <form method="GET" action="{{ route('reportes.establecimiento') }}" class="d-flex gap-2 align-items-center mt-3 mt-sm-0">
            <select name="establecimiento_id" class="form-select form-select-sm">
                @foreach($establecimientosSelect as $est)
                    <option value="{{ $est->id }}" {{ (int)$establecimientoId === (int)$est->id ? 'selected' : '' }}>
                        {{ $est->nombre }} (RBD: {{ $est->RBD }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary" style="min-width: 140px;">
                Ver reporte
            </button>

            <a href="{{ route('reportes.establecimiento.pdf', ['establecimiento_id' => $establecimientoId]) }}"
                class="btn btn-success"
                target="_blank"
                style="min-width: 140px;">
                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
            </a>
        </form>
    @endif
</div>

<hr>

@if(!$establecimiento)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        No hay establecimientos configurados o seleccionados. Crea al menos uno para ver el reporte.
    </div>
    @return
@endif

{{-- ============================= --}}
{{--            KPIs               --}}
{{-- ============================= --}}
<div class="row g-4 mb-4">

    @php
        $kpis = [
            ['title' => 'Alumnos matriculados',   'value' => $totalAlumnos],
            ['title' => 'Funcionarios',           'value' => $totalFuncionarios],
            ['title' => 'Cursos activos',         'value' => $totalCursos],

            ['title' => 'Incidentes registrados', 'value' => $totalIncidentes],
            ['title' => 'Seguimientos emocionales', 'value' => $totalSeguimientos],
            ['title' => 'Derivaciones',           'value' => $totalDerivaciones],
            ['title' => 'Medidas restaurativas',  'value' => $totalMedidas],

            ['title' => 'Novedades de inspectoría', 'value' => $totalNovedades],
            ['title' => 'Eventos asistencia/atrasos', 'value' => $totalAsistencia],
            ['title' => 'Retiros anticipados',    'value' => $totalRetiros],
            ['title' => 'Accidentes escolares',   'value' => $totalAccidentes],
            ['title' => 'Citaciones a apoderados','value' => $totalCitaciones],

            ['title' => 'Conflictos funcionarios', 'value' => $totalConflictosFunc],
            ['title' => 'Conflictos apoderados',   'value' => $totalConflictosApod],
            ['title' => 'Denuncias Ley Karin',     'value' => $totalLeyKarin],
        ];
    @endphp

    @foreach($kpis as $k)
        <div class="col-6 col-sm-4 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h4 class="fw-bold mb-0">{{ $k['value'] }}</h4>
                    <p class="text-muted mb-0 small">{{ $k['title'] }}</p>
                </div>
            </div>
        </div>
    @endforeach

</div>

{{-- ============================= --}}
{{--      GRÁFICOS PRINCIPALES     --}}
{{-- ============================= --}}
<div class="row g-4 mb-4">

    {{-- Incidentes por mes --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header fw-bold">Incidentes por mes</div>
            <div class="card-body">
                <div id="chart_incidentes_mes"></div>
            </div>
        </div>
    </div>

    {{-- Incidentes por tipo --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header fw-bold">Incidentes por tipo</div>
            <div class="card-body">
                <div id="chart_incidentes_tipo"></div>
            </div>
        </div>
    </div>

</div>

{{-- ============================= --}}
{{--     TOP CURSOS CON INCIDENTES --}}
{{-- ============================= --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">Cursos con más incidentes</div>

    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Curso</th>
                    <th class="text-end">Incidentes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingCursos as $idx => $c)
                    <tr>
                        <td>{{ $c->nombre }}</td>
                        <td class="text-end fw-bold">{{ $c->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            No hay registros suficientes para mostrar un ranking de cursos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

{{-- ============================= --}}
{{--        SCRIPTS CHARTS        --}}
{{-- ============================= --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===============================
    //   Incidentes por mes
    // ===============================
    const datosMes = @json($incidentesPorMes);

    if (datosMes.length === 0) {
        const el = document.getElementById('chart_incidentes_mes');
        if (el) {
            el.innerHTML = '<p class="text-muted">No hay datos suficientes para generar este gráfico.</p>';
        }
    } else {
        const categoriasMes = datosMes.map(item => item.mes);
        const valoresMes     = datosMes.map(item => item.total);

        const chartMes = new ApexCharts(
            document.querySelector('#chart_incidentes_mes'),
            {
                chart: {
                    type: 'line',
                    height: 320
                },
                series: [{
                    name: 'Incidentes',
                    data: valoresMes
                }],
                xaxis: {
                    categories: categoriasMes
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                }
            }
        );
        chartMes.render();
    }

    // ===============================
    //   Incidentes por tipo
    // ===============================
    const datosTipo = @json($incidentesPorTipo);

    if (datosTipo.length === 0) {
        const el = document.getElementById('chart_incidentes_tipo');
        if (el) {
            el.innerHTML = '<p class="text-muted">No hay datos suficientes para generar este gráfico.</p>';
        }
    } else {
        const categoriasTipo = datosTipo.map(item => item.tipo_incidente ?? 'Sin tipo');
        const valoresTipo    = datosTipo.map(item => item.total);

        const chartTipo = new ApexCharts(
            document.querySelector('#chart_incidentes_tipo'),
            {
                chart: {
                    type: 'bar',
                    height: 320
                },
                series: [{
                    name: 'Incidentes',
                    data: valoresTipo
                }],
                xaxis: {
                    categories: categoriasTipo
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    bar: {
                        distributed: true
                    }
                }
            }
        );
        chartTipo.render();
    }

});
</script>
@endpush
