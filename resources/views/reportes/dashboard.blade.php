@extends('layouts.app')

@section('title', 'Dashboard Estadístico')

@section('content')

<div class="page-header">
    <h1 class="page-title">Dashboard Estadístico</h1>
    <p class="text-muted">Resumen general del establecimiento</p>
</div>

<hr>

{{-- ========================================= --}}
{{--               TARJETAS KPI                --}}
{{-- ========================================= --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-muted">Incidentes del mes</h6>
                <h3 class="fw-bold">{{ $totalIncidentesMes }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-muted">Seguimientos del mes</h6>
                <h3 class="fw-bold">{{ $totalSeguimientosMes }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-muted">Derivaciones del mes</h6>
                <h3 class="fw-bold">{{ $totalDerivacionesMes }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-muted">Accidentes escolares</h6>
                <h3 class="fw-bold">{{ $totalAccidentesMes }}</h3>
            </div>
        </div>
    </div>

</div>



{{-- ========================================= --}}
{{--         GRÁFICOS PRINCIPALES (2x2)       --}}
{{-- ========================================= --}}
<div class="row g-4">

    {{-- Incidentes por tipo --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">
                Incidentes por tipo (últimos 30 días)
            </div>
            <div class="card-body">
                <div id="chart_incidentes_tipo"></div>
            </div>
        </div>
    </div>

    {{-- Tendencia mensual --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Tendencia mensual (últimos 12 meses)</div>
            <div class="card-body">
                <div id="chart_tendencia_mensual"></div>
            </div>
        </div>
    </div>

</div>


<div class="row g-4 mt-1">

    {{-- Pie Seguimientos --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Seguimientos por nivel emocional</div>
            <div class="card-body">
                <div id="chart_seguimientos_nivel"></div>
            </div>
        </div>
    </div>

    {{-- Dona Derivaciones --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Derivaciones por destino</div>
            <div class="card-body">
                <div id="chart_derivaciones_destino"></div>
            </div>
        </div>
    </div>

    {{-- Heatmap --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Mapa de calor (incidentes por día)</div>
            <div class="card-body">
                <div id="chart_heatmap"></div>
            </div>
        </div>
    </div>

</div>



{{-- ========================================= --}}
{{--               RANKINGS                    --}}
{{-- ========================================= --}}
<div class="row g-4 mt-1">

    {{-- Top cursos --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Cursos con más incidentes</div>
            <div class="card-body">

                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Casos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCursos as $t)
                            <tr>
                                <td>{{ $t->curso->nombre ?? '—' }}</td>
                                <td class="fw-bold">{{ $t->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- Top alumnos --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Alumnos con más incidentes</div>
            <div class="card-body">

                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Casos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topAlumnos as $a)
                            <tr>
                                <td>{{ optional(\App\Models\Alumno::find($a->alumno_id))->nombre_completo ?? '—' }}</td>
                                <td class="fw-bold">{{ $a->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>



{{-- ========================================= --}}
{{--  COMPARACIÓN ENTRE ESTABLECIMIENTOS (Rol 1) --}}
{{-- ========================================= --}}
@if($rol == 1)
<div class="row g-4 mt-1">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold">Comparación entre establecimientos</div>
            <div class="card-body">
                <div id="chart_comparacion_sedes"></div>
            </div>
        </div>
    </div>
</div>
@endif



{{-- ========================================= --}}
{{--       SCRIPTS DE APEXCHARTS               --}}
{{-- ========================================= --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // ==========================================
    //   INCIDENTES POR TIPO
    // ==========================================
    var chartIncidentesTipo = new ApexCharts(document.querySelector("#chart_incidentes_tipo"), {
        chart: { type: 'bar', height: 350 },
        series: [{
            name: "Cantidad",
            data: @json($incidentesPorTipo->pluck('total'))
        }],
        xaxis: {
            categories: @json($incidentesPorTipo->pluck('tipo_incidente'))
        }
    });
    chartIncidentesTipo.render();


    // ==========================================
    //   TENDENCIA MENSUAL
    // ==========================================
    var chartTendencia = new ApexCharts(document.querySelector("#chart_tendencia_mensual"), {
        chart: { type: 'line', height: 350 },
        series: [{
            name: "Incidentes",
            data: @json($tendenciaMensual->pluck('total'))
        }],
        xaxis: {
            categories: @json($tendenciaMensual->pluck('mes'))
        }
    });
    chartTendencia.render();


    // ==========================================
    //   SEGUIMIENTOS POR NIVEL EMOCIONAL
    // ==========================================
    var chartSeguimientos = new ApexCharts(document.querySelector("#chart_seguimientos_nivel"), {
        chart: { type: 'pie', height: 350 },
        series: @json($SeguimientosPorNivel->pluck('total')),
        labels: @json($SeguimientosPorNivel->pluck('nivel.nombre'))
    });
    chartSeguimientos.render();


    // ==========================================
    //   DERIVACIONES POR DESTINO
    // ==========================================
    var chartDerivaciones = new ApexCharts(document.querySelector("#chart_derivaciones_destino"), {
        chart: { type: 'donut', height: 350 },
        series: @json($derivacionesDestino->pluck('total')),
        labels: @json($derivacionesDestino->pluck('destino'))
    });
    chartDerivaciones.render();


    // ==========================================
    //   HEATMAP DÍAS DEL MES
    // ==========================================
    var chartHeatmap = new ApexCharts(document.querySelector("#chart_heatmap"), {
        chart: { type: 'heatmap', height: 350 },
        series: [{
            name: "Incidentes",
            data: @json($heatmap->map(fn($d)=>['x'=>$d->dia,'y'=>$d->total]))
        }],
        colors: ['#008FFB']
    });
    chartHeatmap.render();


    // ==========================================
    //   COMPARACIÓN ENTRE SEDES (Solo rol 1)
    // ==========================================
    @if($rol == 1)
    var chartSedes = new ApexCharts(document.querySelector("#chart_comparacion_sedes"), {
        chart: { type: 'bar', height: 350 },
        series: [{
            name: "Incidentes",
            data: @json($comparacionEstablecimientos->pluck('total'))
        }],
        xaxis: {
            categories: @json($comparacionEstablecimientos->pluck('establecimiento_id'))
        }
    });
    chartSedes.render();
    @endif

</script>

@endsection
