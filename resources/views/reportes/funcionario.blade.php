@extends('layouts.app')

@section('title', 'Reporte por Funcionario')

@section('content')

<div class="page-header">
    <h1 class="page-title">Reporte por Funcionario</h1>
    <p class="text-muted">Análisis completo del trabajador seleccionado</p>
</div>

<hr>

<form method="GET" action="{{ route('reportes.funcionario') }}" class="mb-4">

    <label class="form-label fw-bold">Selecciona un funcionario:</label>

    {{-- Campo oculto con el ID --}}
    <input type="hidden" name="funcionario_id" id="funcionario_id" value="{{ request('funcionario_id') }}">

    <div class="d-flex align-items-center gap-2">

        <div class="flex-grow-1">
            <input type="text" id="funcionario_nombre"
                   class="form-control"
                   placeholder="Buscar funcionario..."
                   value="{{ $funcionarioSeleccionado->nombre_completo ?? '' }}"
                   readonly>
        </div>

        <button type="button"
                class="btn btn-secondary"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarFuncionario">
            Buscar
        </button>

        <button class="btn btn-primary">
            Generar Reporte
        </button>
    </div>
</form>

@if(!$funcionarioSeleccionado)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Selecciona un funcionario para ver su reporte.
    </div>
@endif

@if($funcionarioSeleccionado)

{{-- ============================= --}}
{{--            KPIs               --}}
{{-- ============================= --}}
<div class="row g-4 mb-4">

    @php
        $kpis = [
            ['title' => 'Novedades', 'value' => $novedades],
            ['title' => 'Citaciones realizadas', 'value' => $citaciones],
            ['title' => 'Conflictos Func.', 'value' => $conflictosFuncionarios],
            ['title' => 'Conflictos Apoderados', 'value' => $conflictosApoderados],
            ['title' => 'Denuncias Ley Karin', 'value' => $leyKarin],
            ['title' => 'Accidentes Registrados', 'value' => $accidentes],
            ['title' => 'Retiros entregados', 'value' => $retiros],
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


{{-- =============== Gráfico ================ --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header fw-bold">Novedades por tipo</div>
    <div class="card-body">
        <div id="chart_novedades"></div>
    </div>
</div>

@endif


{{-- ============================= --}}
{{--     LIBRERÍA APEXCHARTS      --}}
{{-- ============================= --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Si la variable NO existe, crear un arreglo vacío
    let data = @json($novedadesPorTipo ?? []);

    if (!Array.isArray(data) || data.length === 0) {
        document.getElementById('chart_novedades').innerHTML =
            '<p class="text-muted">No hay datos suficientes para generar un gráfico.</p>';
        return;
    }

    let categorias = data.map(item => item.tipo);
    let valores = data.map(item => item.total);

    let chartOptions = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Novedades',
            data: valores
        }],
        xaxis: {
            categories: categorias
        },
        colors: ['#4B9DEA']
    };

    let chart = new ApexCharts(
        document.querySelector("#chart_novedades"),
        chartOptions
    );

    chart.render();
});
</script>


{{-- ============================= --}}
{{--     MODAL BUSCAR FUNCIONARIO --}}
{{-- ============================= --}}
@include('reportes.modal-buscar-funcionario')


{{-- SCRIPT SELECCIÓN MODAL --}}
<script>
document.addEventListener('click', function (e) {

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('funcionario_id').value = id;
        document.getElementById('funcionario_nombre').value = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }

});
</script>

@endsection
