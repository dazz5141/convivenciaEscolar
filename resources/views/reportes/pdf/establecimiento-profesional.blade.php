<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Profesional - Convivencia Escolar</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .portada {
            text-align: center;
            padding-top: 120px;
        }
        .titulo-portada {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #003366;
        }
        .subtitulo {
            font-size: 18px;
            margin-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
            border-bottom: 2px solid #003366;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table th, table td {
            border: 1px solid #999;
            padding: 6px;
        }

        table th {
            background: #e9ecef;
        }

        .kpi {
            margin: 5px 0;
            font-size: 16px;
        }

        footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>

{{-- ===========================
     PORTADA
=========================== --}}
<div class="portada">
    <img src="{{ public_path('logo.png') }}" width="120">

    <div class="titulo-portada">Informe Profesional de Convivencia Escolar</div>

    <div class="subtitulo">Establecimiento: <strong>{{ $establecimiento->nombre }}</strong></div>
    <div class="subtitulo">RBD: {{ $establecimiento->RBD }}</div>
    <div class="subtitulo">Comuna: {{ $establecimiento->comuna->nombre ?? 'Sin registro' }}</div>
</div>

<div class="page-break"></div>

{{-- ===========================
     INDICADORES GENERALES
=========================== --}}
<div class="header">1. Indicadores Generales</div>

<p class="kpi">• Total de cursos: <strong>{{ $totalCursos }}</strong></p>
<p class="kpi">• Alumnos matriculados: <strong>{{ $totalAlumnos }}</strong></p>
<p class="kpi">• Funcionarios: <strong>{{ $totalFuncionarios }}</strong></p>

<p class="kpi">• Incidentes registrados: <strong>{{ $totalIncidentes }}</strong></p>
<p class="kpi">• Seguimientos emocionales: <strong>{{ $totalSeguimientos }}</strong></p>
<p class="kpi">• Derivaciones: <strong>{{ $totalDerivaciones }}</strong></p>
<p class="kpi">• Medidas restaurativas: <strong>{{ $totalMedidas }}</strong></p>
<p class="kpi">• Novedades de inspectoría: <strong>{{ $totalNovedades }}</strong></p>
<p class="kpi">• Accidentes escolares: <strong>{{ $totalAccidentes }}</strong></p>

<div class="page-break"></div>

{{-- ===========================
     RANKING DE CURSOS
=========================== --}}
<div class="header">2. Cursos con mayor número de incidentes</div>

<table>
    <thead>
        <tr>
            <th>Curso</th>
            <th>Total incidentes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rankingCursos as $c)
            <tr>
                <td>{{ $c->nombre_curso }}</td>
                <td>{{ $c->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<footer>
    Informe generado automáticamente por el Sistema de Convivencia Escolar — {{ date('d/m/Y') }}
</footer>

</body>
</html>
