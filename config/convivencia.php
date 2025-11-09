<?php

return [

    // Dominio usado al crear usuarios automáticos
    'dominio_email' => 'colegio.cl',

    // Mapa de roles según cargo (IDs reales de tu base)
    // Según tu tabla:
    // 5 = Profesor
    // 4 = Inspector
    // 6 = Psicólogo
    // 7 = Asistente de Aula
    // 2 = Administrador Establecimiento
    // Administrativo (usaremos 7 por ahora, me pediste corregirlo después)

    'roles_por_cargo' => [
        'Profesor'           => 5,
        'Inspector'          => 4,
        'Psicólogo'          => 6,
        'Asistente de Aula'  => 7,
        'Directivo'          => 2,
        'Administrativo'     => 7, // Provisorio (puedes cambiarlo más tarde)
    ],

];
