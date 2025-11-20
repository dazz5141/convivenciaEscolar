@extends('layouts.app')

@section('title', 'Reporte por Curso')

@section('content')

<div class="page-header">
    <h1 class="page-title">Reporte por Curso</h1>
    <p class="text-muted">Análisis completo del curso seleccionado</p>
</div>

<hr>

{{-- ============================= --}}
{{--     FORMULARIO DE FILTRO      --}}
{{-- ============================= --}}
<form method="GET" action="{{ route('reportes.curso') }}" class="mb-4">
    <label class="form-label fw-bold">Selecciona un curso:</label>
    <div class="input-group">
        <select name="curso_id" class="form-select" required>
            <option value="">— Seleccionar —</option>
            @foreach($cursos as $c)
                <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nombre }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-primary">Generar Reporte</button>
    </div>
</form>

@if(!$cursoSeleccionado)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Selecciona un curso para ver el reporte.
    </div>
@endif


@if($cursoSeleccionado)

{{-- ============================= --}}
{{--            KPIs               --}}
{{-- ============================= --}}
<div class="row g-4 mb-4">

    @php
    $kpis = [
        ['title' => 'Incidentes', 'value' => $incidentes],
        ['title' => 'Seguimientos', 'value' => $seguimientos],
        ['title' => 'Derivaciones', 'value' => $derivaciones],
        ['title' => 'Accidentes', 'value' => $accidentes],
        ['title' => 'Citaciones', 'value' => $citaciones],
        ['title' => 'Novedades', 'value' => $novedades],
        ['title' => 'Atrasos', 'value' => $atrasos],
        ['title' => 'Retiros anticipados', 'value' => $retiros],
    ];
    @endphp

    @foreach($kpis as $k)
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $k['value'] }}</h5>
                <p class="text-muted m-0">{{ $k['title'] }}</p>
            </div>
        </div>
    </div>
    @endforeach

</div>

@endif


@if($cursoSeleccionado)
{{-- ============================= --}}
{{--   GRÁFICO INCIDENTES POR TIPO --}}
{{-- ============================= --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">Incidentes por tipo</div>
    <div class="card-body">
        <div id="chart_incidentes_tipo"></div>
    </div>
</div>
@endif


{{-- ============================= --}}
{{--   TABLA DE ALUMNOS DEL CURSO --}}
{{-- ============================= --}}
@if($cursoSeleccionado)

<table class="table table-sm table-hover mt-4">
    <thead>
        <tr>
            <th>Alumno</th>
            <th>RUN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $a)
        <tr>
            <td>{{ $a->nombre_completo }}</td>
            <td>{{ $a->run }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

{{-- ============================= --}}
{{--        SCRIPTS CHART         --}}
{{-- ============================= --}}
@if($cursoSeleccionado)
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
var chart = new ApexCharts(document.querySelector("#chart_incidentes_tipo"), {
    chart: { type: 'bar', height: 350 },
    series: [{
        name: 'Incidentes',
        data: @json($incidentesPorTipo->pluck('total'))
    }],
    xaxis: {
        categories: @json($incidentesPorTipo->pluck('tipo_incidente'))
    }
});
chart.render();
</script>
@endif

@endsection
