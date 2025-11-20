@extends('layouts.app')

@section('title', 'Reporte por Alumno')

@section('content')

<div class="page-header">
    <h1 class="page-title">Reporte por Alumno</h1>
    <p class="text-muted">Análisis completo del estudiante seleccionado</p>
</div>

<hr>

{{-- ============================= --}}
{{--     FORMULARIO DE FILTRO      --}}
{{-- ============================= --}}
<form method="GET" action="{{ route('reportes.alumno') }}" class="mb-4">
    <label class="form-label fw-bold">Selecciona un alumno:</label>

    {{-- Campo oculto que enviará el ID del alumno --}}
    <input type="hidden" name="alumno_id" id="alumno_id" value="{{ request('alumno_id') }}">

    <div class="d-flex align-items-center gap-2">

        <div class="flex-grow-1">
            <input type="text" id="alumno_nombre"
                class="form-control"
                placeholder="Buscar alumno..."
                value="{{ $alumnoSeleccionado->nombre_completo ?? '' }}"
                readonly>
        </div>

        <button type="button" 
                class="btn btn-secondary"
                data-bs-toggle="modal" 
                data-bs-target="#modalBuscarAlumno">
            Buscar
        </button>

        <button class="btn btn-primary">
            Generar Reporte
        </button>

    </div>
</form>

@if(!$alumnoSeleccionado)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Selecciona un alumno para ver su reporte.
    </div>
@endif

@if($alumnoSeleccionado)

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
{{--        SCRIPTS CHART         --}}
{{-- ============================= --}}
@if($alumnoSeleccionado)
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

{{-- ============================= --}}
{{--     MODAL BUSCAR ALUMNO      --}}
{{-- ============================= --}}
@include('reportes.modal-buscar-alumno')

{{-- ============================= --}}
{{--     SCRIPT SELECCION MODAL    --}}
{{-- ============================= --}}
<script>
document.addEventListener('click', function (e) {

    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('alumno_id').value = id;
        document.getElementById('alumno_nombre').value = nombre;

        let modal = bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        );
        modal.hide();
    }
});
</script>

@endsection
