<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ROL 1 — ADMIN GENERAL
    |--------------------------------------------------------------------------
    */
    1 => [
        'nombre' => 'Administrador General',
        'accesos' => [

            // MÓDULO ADMINISTRACIÓN
            'establecimientos'       => ['full'],
            'usuarios'               => ['full'],
            'roles'                  => ['full'],
            'funcionarios'           => ['full'],
            'cursos'                 => ['full'],
            'alumnos'                => ['full'],
            'apoderados'             => ['full'],
            'auditoria'              => ['full'],
            'auditoria_index'        => ['full'],   
            'auditoria_show'         => ['full'], 
            'documentos'             => ['full'],

            // MÓDULO CONVIVENCIA
            'convivencia'            => ['full'],
            'bitacora'               => ['full'],
            'seguimientos'           => ['full'],
            'medidas'                => ['full'],
            'derivaciones'           => ['full'],

            // MÓDULO INSPECTORÍA
            'inspectoria'            => ['full'],
            'novedades'              => ['full'],
            'atrasos'                => ['full'],
            'retiros'                => ['full'],
            'accidentes'             => ['full'],
            'citaciones'             => ['full'],

            // MÓDULO LEY KARIN
            'ley_karin'              => ['full'],
            'conflictos_apoderados'  => ['full'],
            'conflictos_funcionarios'=> ['full'],
            'denuncias'              => ['full'],

            // MÓDULO PIE
            'pie'                    => ['full'],
            'pie-estudiantes'        => ['full'],
            'pie-profesionales'      => ['full'],
            'pie-intervenciones'     => ['full'],
            'pie-informes'           => ['full'],
            'pie-planes'             => ['full'],
            'pie-derivaciones'       => ['full'],

            // REPORTES
            'reportes'               => ['full'],
            'reporte_curso'          => ['full'],
            'reporte_alumno'         => ['full'],
            'reporte_funcionario'    => ['full'],
            'reporte_establecimiento'=> ['full'],
            'dashboard_estadistico'  => ['full'],

            // MÓDULO FUNCIONARIOS
            'funcionarios_index'   => ['full'],
            'funcionarios_create'  => ['full'],
            'funcionarios_edit'    => ['full'],
            'funcionarios_show'    => ['full'],
            'funcionarios_disable' => ['full'],
            'funcionarios_enable'  => ['full'],

            // MÓDULO USUARIOS
            'usuarios_index'   => ['full'],
            'usuarios_create'  => ['full'],
            'usuarios_edit'    => ['full'],
            'usuarios_show'    => ['full'],
            'usuarios_disable' => ['full'],
            'usuarios_enable'  => ['full'],

            // MÓDULO ESTABLECIMIENTOS
            'establecimientos_index'   => ['full'],
            'establecimientos_create'  => ['full'],
            'establecimientos_edit'    => ['full'],
            'establecimientos_show'    => ['full'],
            'establecimientos_disable' => ['full'],
            'establecimientos_enable'  => ['full'],

            // MÓDULO ROLES
            'roles_index'   => ['full'],
            'roles_create'  => ['full'],
            'roles_edit'    => ['full'],
            'roles_show'    => ['full'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 2 — ADMINISTRADOR DEL ESTABLECIMIENTO
    |--------------------------------------------------------------------------
    */
    2 => [
        'nombre' => 'Administrador del Establecimiento',
        'accesos' => [

            // =======================================================
            // ADMINISTRACIÓN LOCAL DEL ESTABLECIMIENTO
            // =======================================================
            'usuarios'           => ['view', 'create', 'edit', 'disable', 'enable'],
            'funcionarios'       => ['view', 'create', 'edit', 'disable', 'enable'],
            'cursos'             => ['view', 'create', 'edit', 'delete'],
            'alumnos'            => ['view', 'create', 'edit', 'delete'],
            'apoderados'         => ['view', 'create', 'edit', 'delete'],
            'documentos'         => ['view', 'create', 'edit', 'delete'],

            // =======================================================
            // AUDITORÍA — SOLO DEL ESTABLECIMIENTO
            // =======================================================
            'auditoria'       => ['view'],
            'auditoria_index' => ['view'],
            'auditoria_show'  => ['view'],

            // =======================================================
            // MÓDULO DE CONVIVENCIA COMPLETO
            // =======================================================
            'convivencia'        => ['full'],
            'bitacora'           => ['full'],
            'seguimientos'       => ['full'],
            'medidas'            => ['full'],
            'derivaciones'       => ['full'],

            // =======================================================
            // INSPECTORÍA COMPLETA
            // =======================================================
            'inspectoria'        => ['full'],
            'novedades'          => ['full'],
            'accidentes'         => ['full'],
            'retiros'            => ['full'],
            'atrasos'            => ['full'],
            'citaciones'         => ['full'],

            // =======================================================
            // LEY KARIN COMPLETA
            // =======================================================
            'ley_karin'                => ['full'],
            'conflictos_apoderados'    => ['full'],
            'conflictos_funcionarios'  => ['full'],
            'denuncias'                => ['full'],

            // =======================================================
            // MÓDULO PIE COMPLETO
            // =======================================================
            'pie'                 => ['full'],
            'pie-estudiantes'     => ['full'],
            'pie-profesionales'   => ['full'],
            'pie-intervenciones'  => ['full'],
            'pie-informes'        => ['full'],
            'pie-planes'          => ['full'],
            'pie-derivaciones'    => ['full'],

            // =======================================================
            // REPORTES COMPLETOS
            // =======================================================
            'reportes'                => ['view'],
            'reporte_curso'           => ['view'],
            'reporte_alumno'          => ['view'],
            'reporte_funcionario'     => ['view'],
            'reporte_establecimiento' => ['view'],
            'dashboard_estadistico'   => ['view'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 3 — INSPECTOR GENERAL
    |--------------------------------------------------------------------------
    */
    3 => [
        'nombre' => 'Inspector General',
        'accesos' => [

            // =======================================================
            // INSPECTORÍA — ACCESO COMPLETO
            // =======================================================
            'inspectoria'   => ['full'],
            'novedades'     => ['full'],
            'accidentes'    => ['full'],
            'retiros'       => ['full'],
            'atrasos'       => ['full'],
            'citaciones'    => ['full'],

            // =======================================================
            // CONVIVENCIA — ACCESO INTERMEDIO
            // =======================================================
            'bitacora'      => ['view', 'create', 'edit'],   // No elimina
            'seguimientos'  => ['view'],                     // No edita
            'medidas'       => ['view'],                     // No crea ni edita
            'derivaciones'  => ['view', 'create'],           // Puede derivar casos

            // =======================================================
            // PIE — SOLO CONSULTA
            // =======================================================
            'pie'                => ['view'],
            'pie-estudiantes'    => ['view'],
            'pie-derivaciones'   => ['view'],

            // =======================================================
            // ALUMNOS Y APODERADOS — SOLO VIEW
            // =======================================================
            'alumnos'      => ['view'],
            'apoderados'   => ['view'],

            // =======================================================
            // REPORTES — SOLO CONSULTA
            // =======================================================
            'reportes'                => ['view'],
            'reporte_curso'           => ['view'],
            'reporte_alumno'          => ['view'],
            'reporte_funcionario'     => ['view'],
            'reporte_establecimiento' => ['view'],
            'dashboard_estadistico'   => ['view'],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | ROL 4 — INSPECTOR
    |--------------------------------------------------------------------------
    */
    4 => [
        'nombre' => 'Inspector',
        'accesos' => [

            // =======================================================
            // INSPECTORÍA — ACCESO OPERATIVO COMPLETO
            // =======================================================
            'inspectoria'   => ['view', 'create', 'edit'],   // No elimina
            'novedades'     => ['view', 'create', 'edit'],   // Registra novedades diarias
            'accidentes'    => ['view', 'create', 'edit'],   // Registra accidentes escolares
            'retiros'       => ['view', 'create', 'edit'],   // Retiros anticipados
            'atrasos'       => ['view', 'create', 'edit'],   // Control de atrasos
            'citaciones'    => ['view', 'create', 'edit'],   // Citaciones a apoderados

            // =======================================================
            // CONVIVENCIA — ACCESO LIMITADO
            // =======================================================
            'bitacora'      => ['view', 'create'],           // Puede registrar incidentes
            'seguimientos'  => ['view'],                     // Solo lectura
            'medidas'       => ['view'],                     // Solo lectura
            'derivaciones'  => ['view'],                     // Solo ver derivaciones

            // =======================================================
            // ALUMNOS Y APODERADOS — SOLO CONSULTA
            // =======================================================
            'alumnos'       => ['view'],
            'apoderados'    => ['view'],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | ROL 5 — PROFESOR
    |--------------------------------------------------------------------------
    */
    5 => [
        'nombre' => 'Profesor',
        'accesos' => [

            // =======================================================
            // CONVIVENCIA — SOLO CONSULTA
            // =======================================================
            'bitacora'      => ['view'],   // Puede ver incidentes de sus alumnos
            'seguimientos'  => ['view'],   // Ve seguimientos emocionales
            'medidas'       => ['view'],   // Ve medidas restaurativas
            'derivaciones'  => ['view'],   // Solo lectura de derivaciones

            // =======================================================
            // INSPECTORÍA — SOLO CONSULTA
            // (Opcional en algunos colegios, profesionalmente SÍ pueden ver)
            // =======================================================
            //'inspectoria'   => ['view'],   // Ve registros de disciplina del curso
            //'novedades'     => ['view'],
            //'accidentes'    => ['view'],
            //'retiros'       => ['view'],
            //'atrasos'       => ['view'],
            //'citaciones'    => ['view'],

            // =======================================================
            // ALUMNOS Y APODERADOS — CONSULTA
            // =======================================================
            'alumnos'       => ['view'],   // Ve datos de sus estudiantes
            'apoderados'    => ['view'],   // Ve datos del apoderado
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 6 — PSICÓLOGO / PIE
    |--------------------------------------------------------------------------
    */
    6 => [
        'nombre' => 'Psicólogo / PIE',
        'accesos' => [

            // =======================================================
            // CONVIVENCIA — ACCESO PROFESIONAL
            // =======================================================
            'bitacora'          => ['view'],                 // Solo lectura
            'seguimientos'      => ['view', 'create', 'edit'], // Puede crear y editar seguimientos emocionales
            'medidas'           => ['view'],                 // Solo lectura
            'derivaciones'      => ['view', 'create'],       // Puede derivar a PIE

            // =======================================================
            // PIE — ACCESO COMPLETO (ROL PRINCIPAL)
            // =======================================================
            'pie'                => ['full'],
            'pie-estudiantes'    => ['full'],
            'pie-profesionales'  => ['full'],
            'pie-intervenciones' => ['full'],
            'pie-informes'       => ['full'],
            'pie-planes'         => ['full'],
            'pie-derivaciones'   => ['full'],

            // =======================================================
            // DOCUMENTOS — ACCESO CONTROLADO
            // =======================================================
            'documentos'         => ['view', 'create'],      // Puede subir informes, planes, etc.

            // =======================================================
            // ALUMNOS / APODERADOS — SOLO CONSULTA
            // =======================================================
            'alumnos'            => ['view'],
            'apoderados'         => ['view'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 7 — ASISTENTE DE AULA (DESHABILITADO POR DEFECTO)
    |--------------------------------------------------------------------------
    |
    | Este rol está deshabilitado por ahora porque la mayoría de los colegios
    | no entrega acceso a asistentes de aula. Se deja comentado para que
    | pueda ser habilitado fácilmente en el futuro si se requiere.
    |
    */
    /*
    7 => [
        'nombre' => 'Asistente de Aula',
        'accesos' => [

            'alumnos'      => ['view'],
            'apoderados'   => ['view'],
            'bitacora'     => ['view'],

        ],
    ],
    */

    /*
    |--------------------------------------------------------------------------
    | ROL 8 — ENCARGADO DE CONVIVENCIA
    |--------------------------------------------------------------------------
    */
    8 => [
        'nombre' => 'Encargado de Convivencia Escolar',
        'accesos' => [

            // =======================================================
            // CONVIVENCIA — ACCESO COMPLETO
            // =======================================================
            'convivencia'        => ['full'],
            'bitacora'           => ['full'],
            'seguimientos'       => ['full'],
            'medidas'            => ['full'],
            'derivaciones'       => ['full'],
            'documentos'         => ['view', 'create', 'edit', 'delete'],

            // =======================================================
            // INSPECTORÍA — ACCESO DE SUPERVISIÓN
            // =======================================================
            'inspectoria'        => ['view'],
            'novedades'          => ['view'],
            'accidentes'         => ['view'],
            'retiros'            => ['view'],
            'atrasos'            => ['view'],
            'citaciones'         => ['view'],

            // =======================================================
            // PIE — ACCESO DE ARTICULACIÓN
            // =======================================================
            'pie'                => ['view', 'edit'],     // Puede coordinar casos
            'pie-estudiantes'    => ['view'],
            'pie-intervenciones' => ['view'],
            'pie-planes'         => ['view'],
            'pie-derivaciones'   => ['view', 'create'],   // Puede derivar hacia PIE

            // =======================================================
            // REPORTES — ACCESO A REPORTES DE CONVIVENCIA
            // =======================================================
            'reportes'                => ['view'],
            'reporte_curso'           => ['view'],
            'reporte_alumno'          => ['view'],
            'dashboard_estadistico'   => ['view'],

            // =======================================================
            // ALUMNOS/APODERADOS — CONSULTA
            // =======================================================
            'alumnos'            => ['view'],
            'apoderados'         => ['view'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 9 — UTP (Unidad Técnico Pedagógica)
    |--------------------------------------------------------------------------
    */
    9 => [
        'nombre' => 'UTP',
        'accesos' => [

            // =======================================================
            // CONVIVENCIA — SOLO LECTURA (MONITOREO)
            // =======================================================
            'bitacora'          => ['view'],   // Ve incidentes
            'seguimientos'      => ['view'],   // Ve seguimientos emocionales
            'medidas'           => ['view'],   // Ve medidas restaurativas
            'derivaciones'      => ['view'],   // Ve derivaciones internas

            // =======================================================
            // INSPECTORÍA — SOLO CONSULTA
            // =======================================================
            'inspectoria'       => ['view'],
            'novedades'         => ['view'],
            'accidentes'        => ['view'],
            'retiros'           => ['view'],
            'atrasos'           => ['view'],
            'citaciones'        => ['view'],

            // =======================================================
            // PIE — CONSULTA PROFESIONAL
            // =======================================================
            'pie'                => ['view'],
            'pie-estudiantes'    => ['view'],
            'pie-intervenciones' => ['view'],
            'pie-planes'         => ['view'],
            'pie-derivaciones'   => ['view'],

            // =======================================================
            // REPORTES — CONSULTA
            // =======================================================
            'reportes'                => ['view'],
            'reporte_curso'           => ['view'],
            'reporte_alumno'          => ['view'],
            'dashboard_estadistico'   => ['view'],

            // =======================================================
            // ALUMNOS Y APODERADOS — CONSULTA
            // =======================================================
            'alumnos'           => ['view'],
            'apoderados'        => ['view'],
        ],
    ],
];
