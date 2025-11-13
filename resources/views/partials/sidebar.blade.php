<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-check"></i>
        Convivencia Escolar
    </div>

    <ul class="sidebar-menu">

        <!-- ====================== -->
        <!--   MÓDULOS PRINCIPALES  -->
        <!-- ====================== -->

        <li>
            <a href="/dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="/modulos/bitacora">
                <i class="bi bi-journal-text"></i>
                <span>Bitácora Incidentes</span>
            </a>
        </li>

        <li>
            <a href="/modulos/seguimiento-emocional">
                <i class="bi bi-heart-pulse"></i>
                <span>Seguimiento Emocional</span>
            </a>
        </li>

        <li>
            <a href="/modulos/medidas-restaurativas">
                <i class="bi bi-patch-check"></i>
                <span>Medidas Restaurativas</span>
            </a>
        </li>

        <li>
            <a href="/modulos/derivaciones">
                <i class="bi bi-arrow-right-circle"></i>
                <span>Derivaciones</span>
            </a>
        </li>

        <!-- ============================== -->
        <!--         INSPECTORÍA            -->
        <!-- ============================== -->

        <li class="submenu" data-key="inspectoria">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-building-lock"></i>
                <span>Inspectoría</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">
                <li><a href="/modulos/novedades"><i class="bi bi-book"></i> Libro de Novedades</a></li>
                <li><a href="/modulos/atrasos"><i class="bi bi-clock-history"></i> Atrasos / Asistencia</a></li>
                <li><a href="/modulos/retiros"><i class="bi bi-door-open"></i> Retiros Anticipados</a></li>
                <li><a href="/modulos/accidentes"><i class="bi bi-bandaid"></i> Accidentes Escolares</a></li>
                <li><a href="/modulos/citaciones"><i class="bi bi-calendar-check"></i> Citaciones Apoderados</a></li>
            </ul>
        </li>

        <!-- ============================== -->
        <!--            LEY KARIN           -->
        <!-- ============================== -->

        <li class="submenu" data-key="leykarin">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-exclamation-octagon"></i>
                <span>Ley Karin</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">
                <li><a href="/modulos/conflicto-apoderado"><i class="bi bi-people"></i> Conflicto Apoderado</a></li>
                <li><a href="/modulos/conflicto-funcionario"><i class="bi bi-person-badge"></i> Conflicto Funcionario</a></li>
                <li><a href="/modulos/denuncia-ley-karin"><i class="bi bi-exclamation-triangle"></i> Denuncias Ley Karin</a></li>
            </ul>
        </li>

        <!-- ============================== -->
        <!--               PIE              -->
        <!-- ============================== -->

        <li class="submenu" data-key="pie">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-clipboard2-pulse"></i>
                <span>Módulo PIE</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">
                <li><a href="{{ route('pie.profesionales.index') }}"><i class="bi bi-people-fill"></i> Profesionales PIE</a></li>
                <li><a href="{{ route('pie.estudiantes.index') }}"><i class="bi bi-person-badge"></i> Estudiantes PIE</a></li>
                <li><a href="{{ route('pie.intervenciones.index') }}"><i class="bi bi-journal-medical"></i> Intervenciones PIE</a></li>
                <li><a href="{{ route('pie.informes.index') }}"><i class="bi bi-file-earmark-text"></i> Informes PIE</a></li>
                <li><a href="{{ route('pie.planes.index') }}"><i class="bi bi-clipboard-check"></i> Planes Individuales</a></li>
                <li><a href="{{ route('pie.derivaciones.index') }}"><i class="bi bi-arrow-left-right"></i> Derivaciones PIE</a></li>
            </ul>
        </li>

        <!-- ============================== -->
        <!--            REPORTES            -->
        <!-- ============================== -->

        <li class="submenu" data-key="reportes">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-bar-chart"></i>
                <span>Reportes</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">
                <li><a href="/modulos/reportes/curso"><i class="bi bi-list-ul"></i> Reportes por Curso</a></li>
                <li><a href="/modulos/reportes/alumno"><i class="bi bi-person-lines-fill"></i> Reportes por Alumno</a></li>
                <li><a href="/modulos/reportes/funcionario"><i class="bi bi-briefcase"></i> Reportes por Funcionario</a></li>
                <li><a href="/modulos/reportes/establecimiento"><i class="bi bi-building"></i> Reportes por Establecimiento</a></li>
                <li><a href="/modulos/reportes/dashboard"><i class="bi bi-graph-up"></i> Dashboard Estadístico</a></li>
            </ul>
        </li>

        <!-- ========================= -->
        <!--  MÓDULOS ADMINISTRATIVOS  -->
        <!-- ========================= -->

        <li><a href="/modulos/alumnos"><i class="bi bi-mortarboard"></i> Alumnos</a></li>
        <li><a href="/modulos/apoderados"><i class="bi bi-people-fill"></i> Apoderados</a></li>
        <li><a href="/modulos/funcionarios"><i class="bi bi-person-workspace"></i> Funcionarios</a></li>
        <li><a href="/modulos/cursos"><i class="bi bi-bookmark"></i> Cursos</a></li>
        <li><a href="/modulos/establecimientos"><i class="bi bi-building"></i> Establecimientos</a></li>
        <li><a href="/modulos/usuarios"><i class="bi bi-person-circle"></i> Usuarios</a></li>
        <li><a href="/modulos/roles"><i class="bi bi-key"></i> Roles</a></li>
        <li><a href="/modulos/auditoria"><i class="bi bi-file-earmark-text"></i> Auditoría</a></li>

        <li>
            <a href="/modulos/documentos">
                <i class="bi bi-paperclip"></i> Documentos Adjuntos
            </a>
        </li>

    </ul>
</aside>

<div class="sidebar-overlay"></div>
