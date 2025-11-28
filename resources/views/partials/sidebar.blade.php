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


        <!-- ============================== -->
        <!--      CONVIVENCIA ESCOLAR      -->
        <!-- ============================== -->
        @menuAny(['bitacora','seguimientos','medidas','derivaciones'])
        <li class="submenu" data-key="convivencia">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-people-fill"></i>
                <span>Convivencia Escolar</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">

                {{-- BITÁCORA --}}
                @menu('bitacora')
                <li>
                    <a href="{{ route('convivencia.bitacora.index') }}">
                        <i class="bi bi-journal-text"></i> Bitácora Incidentes
                    </a>
                </li>
                @endmenu

                {{-- SEGUIMIENTO EMOCIONAL --}}
                @menu('seguimientos')
                <li>
                    <a href="{{ route('convivencia.seguimiento.index') }}">
                        <i class="bi bi-heart-pulse"></i> Seguimiento Emocional
                    </a>
                </li>
                @endmenu

                {{-- MEDIDAS RESTAURATIVAS --}}
                @menu('medidas')
                <li>
                    <a href="{{ route('convivencia.medidas.index') }}">
                        <i class="bi bi-patch-check"></i> Medidas Restaurativas
                    </a>
                </li>
                @endmenu

                {{-- DERIVACIONES --}}
                @menu('derivaciones')
                <li>
                    <a href="{{ route('convivencia.derivaciones.index') }}">
                        <i class="bi bi-arrow-right-circle"></i> Derivaciones
                    </a>
                </li>
                @endmenu

            </ul>
        </li>
        @endmenuAny


        <!-- ============================== -->
        <!--            INSPECTORÍA         -->
        <!-- ============================== -->
        @menu('inspectoria')
        <li class="submenu" data-key="inspectoria">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-building-lock"></i>
                <span>Inspectoría</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">

                @menu('novedades')
                <li>
                    <a href="{{ route('inspectoria.novedades.index') }}">
                        <i class="bi bi-book"></i> Libro de Novedades
                    </a>
                </li>
                @endmenu

                @menu('atrasos')
                <li>
                    <a href="{{ route('inspectoria.asistencia.index') }}">
                        <i class="bi bi-clock-history"></i> Atrasos / Asistencia
                    </a>
                </li>
                @endmenu

                @menu('retiros')
                <li>
                    <a href="{{ route('inspectoria.retiros.index') }}">
                        <i class="bi bi-door-open"></i> Retiros Anticipados
                    </a>
                </li>
                @endmenu

                @menu('accidentes')
                <li>
                    <a href="{{ route('inspectoria.accidentes.index') }}">
                        <i class="bi bi-bandaid"></i> Accidentes Escolares
                    </a>
                </li>
                @endmenu

                @menu('citaciones')
                <li>
                    <a href="{{ route('inspectoria.citaciones.index') }}">
                        <i class="bi bi-calendar-check"></i> Citaciones Apoderados
                    </a>
                </li>
                @endmenu

            </ul>
        </li>
        @endmenu


        <!-- ============================== -->
        <!--            LEY KARIN           -->
        <!-- ============================== -->
        @menu('ley_karin')
        <li class="submenu" data-key="leykarin">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-exclamation-octagon"></i>
                <span>Ley Karin</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">

                @menu('conflictos_apoderados')
                <li>
                    <a href="{{ route('leykarin.conflictos-apoderados.index') }}">
                        <i class="bi bi-people"></i> Conflictos Apoderados
                    </a>
                </li>
                @endmenu

                @menu('conflictos_funcionarios')
                <li>
                    <a href="{{ route('leykarin.conflictos-funcionarios.index') }}">
                        <i class="bi bi-person-badge"></i> Conflictos Funcionarios
                    </a>
                </li>
                @endmenu

                @menu('denuncias')
                <li>
                    <a href="{{ route('leykarin.denuncias.index') }}">
                        <i class="bi bi-exclamation-triangle"></i> Denuncias Ley Karin
                    </a>
                </li>
                @endmenu

            </ul>
        </li>
        @endmenu


        <!-- ============================== -->
        <!--               PIE              -->
        <!-- ============================== -->
        @menu('pie')
        <li class="submenu" data-key="pie">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-clipboard2-pulse"></i>
                <span>Módulo PIE</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">

                @menu('pie')
                <li><a href="{{ route('pie.profesionales.index') }}"><i class="bi bi-people-fill"></i> Profesionales PIE</a></li>
                @endmenu

                @menu('pie')
                <li><a href="{{ route('pie.estudiantes.index') }}"><i class="bi bi-person-badge"></i> Estudiantes PIE</a></li>
                @endmenu

                @menu('pie')
                <li><a href="{{ route('pie.intervenciones.index') }}"><i class="bi bi-journal-medical"></i> Intervenciones PIE</a></li>
                @endmenu

                @menu('pie')
                <li><a href="{{ route('pie.informes.index') }}"><i class="bi bi-file-earmark-text"></i> Informes PIE</a></li>
                @endmenu

                @menu('pie')
                <li><a href="{{ route('pie.planes.index') }}"><i class="bi bi-clipboard-check"></i> Planes Individuales</a></li>
                @endmenu

                @menu('pie')
                <li><a href="{{ route('pie.derivaciones.index') }}"><i class="bi bi-arrow-left-right"></i> Derivaciones PIE</a></li>
                @endmenu

            </ul>
        </li>
        @endmenu


        <!-- ============================== -->
        <!--            REPORTES            -->
        <!-- ============================== -->
        @menu('reportes')
        <li class="submenu" data-key="reportes">
            <a href="#" class="submenu-toggle">
                <i class="bi bi-bar-chart"></i>
                <span>Reportes</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <ul class="submenu-items">

                @menu('reporte_curso')
                <li>
                    <a href="{{ route('reportes.curso') }}">
                        <i class="bi bi-list-ul"></i> Reportes por Curso
                    </a>
                </li>
                @endmenu

                @menu('reporte_alumno')
                <li>
                    <a href="{{ route('reportes.alumno') }}">
                        <i class="bi bi-person-lines-fill"></i> Reportes por Alumno
                    </a>
                </li>
                @endmenu

                @menu('reporte_funcionario')
                <li>
                    <a href="{{ route('reportes.funcionario') }}">
                        <i class="bi bi-briefcase"></i> Reportes por Funcionario
                    </a>
                </li>
                @endmenu

                @menu('reporte_establecimiento')
                <li>
                    <a href="{{ route('reportes.establecimiento') }}">
                        <i class="bi bi-building"></i> Reportes por Establecimiento
                    </a>
                </li>
                @endmenu

                @menu('dashboard_estadistico')
                <li>
                    <a href="{{ route('reportes.dashboard') }}">
                        <i class="bi bi-graph-up"></i> Dashboard Estadístico
                    </a>
                </li>
                @endmenu

            </ul>
        </li>
        @endmenu

        <!-- ========================= -->
        <!--  MÓDULOS ADMINISTRATIVOS  -->
        <!-- ========================= -->

        @menu('alumnos')
        <li>
            <a href="/modulos/alumnos"><i class="bi bi-mortarboard"></i> Alumnos</a>
        </li>
        @endmenu

        @menu('apoderados')
        <li>
            <a href="/modulos/apoderados"><i class="bi bi-people-fill"></i> Apoderados</a>
        </li>
        @endmenu

        @menu('funcionarios')
        <li>
            <a href="/modulos/funcionarios"><i class="bi bi-person-workspace"></i> Funcionarios</a>
        </li>
        @endmenu

        @menu('cursos')
        <li>
            <a href="/modulos/cursos"><i class="bi bi-bookmark"></i> Cursos</a>
        </li>
        @endmenu

        @menu('establecimientos')
        <li>
            <a href="/modulos/establecimientos"><i class="bi bi-building"></i> Establecimientos</a>
        </li>
        @endmenu

        @menu('usuarios')
        <li>
            <a href="/modulos/usuarios"><i class="bi bi-person-circle"></i> Usuarios</a>
        </li>
        @endmenu

        @menu('roles')
        <li>
            <a href="/modulos/roles"><i class="bi bi-key"></i> Roles</a>
        </li>
        @endmenu

        @menu('auditoria')
        <li>
            <a href="/modulos/auditoria"><i class="bi bi-file-earmark-text"></i> Auditoría</a>
        </li>
        @endmenu

        @menu('documentos')
        <li>
            <a href="/modulos/documentos"><i class="bi bi-paperclip"></i> Documentos Adjuntos</a>
        </li>
        @endmenu

    </ul>
</aside>

<div class="sidebar-overlay"></div>
