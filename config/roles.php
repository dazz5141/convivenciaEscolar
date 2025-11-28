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
            'usuarios'         => ['view', 'create', 'edit', 'delete'],
            'funcionarios'     => ['view', 'create', 'edit', 'delete'],
            'cursos'           => ['view', 'create', 'edit', 'delete'],
            'alumnos'          => ['view', 'create', 'edit', 'delete'],
            'apoderados'       => ['view', 'create', 'edit', 'delete'],
            'convivencia'      => ['full'],
            'inspectoria'      => ['full'],
            'ley_karin'        => ['full'],
            'pie'              => ['full'],
            'reportes'         => ['full'],
            'documentos'       => ['full'],
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
            'bitacora'         => ['view', 'create', 'edit'],
            'seguimientos'     => ['view', 'edit'],
            'medidas'          => ['view', 'create'],
            'derivaciones'     => ['view', 'create'],
            'inspectoria'      => ['full'],
            'reportes'         => ['view'],
            'alumnos'          => ['view'],
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
            'bitacora'         => ['view', 'create'],
            'seguimientos'     => ['view'],
            'medidas'          => ['view', 'create'],
            'inspectoria'      => ['view', 'create', 'edit'],
            'accidentes'       => ['view', 'create', 'edit'],
            'retiros'          => ['view', 'create', 'edit'],
            'atrasos'          => ['view', 'create', 'edit'],
            'novedades'        => ['view', 'create', 'edit'],
            'citaciones'       => ['view', 'create', 'edit'],
            'alumnos'          => ['view'],
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
            'bitacora'         => ['view'],  
            'seguimientos'     => ['view'],
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
            'seguimientos'       => ['view', 'create', 'edit'],
            'intervenciones'     => ['view', 'create'],
            'pie'                => ['view', 'create', 'edit'],

            // SUBMÓDULOS PIE
            'pie-estudiantes'    => ['view', 'create'],
            'pie-profesionales'  => ['view', 'create'],
            'pie-intervenciones' => ['view', 'create'],
            'pie-informes'       => ['view', 'create'],
            'pie-planes'         => ['view', 'create'],
            'pie-derivaciones'   => ['view', 'create'],

            'documentos'         => ['view', 'create'],
            'derivaciones'       => ['view', 'create'],
            'bitacora'           => ['view'],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | ROL 7 — ASISTENTE DE AULA
    |--------------------------------------------------------------------------
    */
    7 => [
        'nombre' => 'Asistente de Aula',
        'accesos' => [
            'bitacora'         => ['view'], // antes view_basic
            'alumnos'          => ['view'],
            'apoderados'       => ['view'],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | ROL 8 — ENCARGADO DE CONVIVENCIA
    |--------------------------------------------------------------------------
    */
    8 => [
        'nombre' => 'Encargado de Convivencia',
        'accesos' => [
            'convivencia'      => ['full'],
            'bitacora'         => ['full'],
            'seguimientos'     => ['full'],
            'medidas'          => ['full'],
            'derivaciones'     => ['full'],
            'pie'              => ['view', 'edit'],

            'ley_karin'                => ['full'],
            'conflictos_apoderados'    => ['full'],
            'conflictos_funcionarios'  => ['full'],
            'denuncias'                => ['full'],

            'reportes'         => ['full'],
            'documentos'       => ['view', 'create'],
            'alumnos'          => ['view'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ROL 9 — UTP
    |--------------------------------------------------------------------------
    */
    9 => [
        'nombre' => 'UTP',
        'accesos' => [
            'bitacora'         => ['view'],
            'seguimientos'     => ['view'],
            'medidas'          => ['view'],
            'derivaciones'     => ['view'],
            'citaciones'       => ['view'],
            'inspectoria'      => ['view'],
            'pie'              => ['view'],
            'reportes'         => ['view'],
            'alumnos'          => ['view'],
            'documentos'       => ['view'],
        ],
    ],

];
