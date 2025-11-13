@extends('layouts.app')

@section('title', 'Historial PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Historial PIE</h1>
        <p class="text-muted">Registro completo del estudiante en el Programa de Integración Escolar.</p>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>


{{-- ============================
    INFORMACIÓN DEL ESTUDIANTE
============================ --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Datos del Estudiante</h5>
    </div>

    <div class="card-body">

        <div class="row g-3">
            <div class="col-md-4">
                <strong>Nombre:</strong><br>
                {{ $estudiantePie->alumno->apellido_paterno ?? '' }}
                {{ $estudiantePie->alumno->apellido_materno ?? '' }},
                {{ $estudiantePie->alumno->nombre ?? '' }}
            </div>

            <div class="col-md-4">
                <strong>RUN:</strong><br>
                {{ $estudiantePie->alumno->run ?? '—' }}
            </div>

            <div class="col-md-4">
                <strong>Curso:</strong><br>
                {{ $estudiantePie->alumno->curso->nombre ?? 'Sin curso' }}
            </div>
        </div>

    </div>
</div>



{{-- ============================
        LÍNEA DE TIEMPO
============================ --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Línea de Tiempo PIE</h5>
    </div>

    <div class="card-body">

        @php
            use Illuminate\Support\Str;

            $eventos = [];

            // INTERVENCIONES
            foreach ($estudiantePie->intervenciones as $i) {
                $eventos[] = [
                    'fecha' => $i->fecha,
                    'tipo' => 'Intervención',
                    'detalle' => $i->detalle,
                    'extra' => $i->profesional->nombre_Completo ?? 'Sin profesional',
                ];
            }

            // INFORMES
            foreach ($estudiantePie->informes as $inf) {
                $eventos[] = [
                    'fecha' => $inf->fecha,
                    'tipo' => 'Informe',
                    'detalle' => Str::limit($inf->contenido, 120),
                    'extra' => $inf->tipo,
                ];
            }

            // PLANES
            foreach ($estudiantePie->planes as $p) {
                $eventos[] = [
                    'fecha' => $p->fecha_inicio,
                    'tipo' => 'Plan Individual',
                    'detalle' => Str::limit($p->objetivos, 120),
                    'extra' => $p->fecha_termino ? 'Finalizado' : 'En curso',
                ];
            }

            // DERIVACIONES
            foreach ($estudiantePie->derivaciones as $d) {
                $eventos[] = [
                    'fecha' => $d->fecha,
                    'tipo' => 'Derivación',
                    'detalle' => $d->motivo,
                    'extra' => $d->destino,
                ];
            }

            // ORDENAR (DESC)
            usort($eventos, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));
        @endphp


        {{-- SIN EVENTOS --}}
        @if(count($eventos) === 0)
            <p class="text-muted">No hay registros PIE para este estudiante.</p>

        {{-- CON EVENTOS --}}
        @else
            <ul class="timeline">

                @foreach ($eventos as $ev)
                    <li class="timeline-item mb-4">

                        <div class="timeline-date">
                            {{ \Carbon\Carbon::parse($ev['fecha'])->format('d/m/Y') }}
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $ev['tipo'] }}</h6>

                                <p class="mb-1">{{ $ev['detalle'] }}</p>

                                <span class="text-muted small">
                                    {{ $ev['extra'] }}
                                </span>
                            </div>
                        </div>

                    </li>
                @endforeach

            </ul>
        @endif

    </div>
</div>


{{-- ============================
        ESTILO TIMELINE
============================ --}}
<style>
.timeline { list-style: none; padding-left: 0; }
.timeline-item { position: relative; padding-left: 20px; }
.timeline-date { font-weight: bold; margin-bottom: 6px; }

.timeline-item::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 6px;
    width: 10px;
    height: 10px;
    background: #0d6efd;
    border-radius: 50%;
}
</style>

@endsection
