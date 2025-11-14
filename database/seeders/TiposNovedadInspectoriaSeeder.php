<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposNovedadInspectoriaSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Atraso',
            'Falta de uniforme',
            'Falta de presentación personal',
            'Desorden en sala',
            'Desacato',
            'Conducta inapropiada',
            'Agresión verbal',
            'Agresión física',
            'Alumno fuera de sala',
            'Alumno sin autorización',
            'Alumno derivado a inspectoría',
            'Falta de útiles',
            'Daños en infraestructura',
            'Situación de riesgo',
            'Observación general'
        ];

        foreach ($tipos as $t) {
            DB::table('tipos_novedad_inspectoria')->insert([
                'nombre' => $t,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
