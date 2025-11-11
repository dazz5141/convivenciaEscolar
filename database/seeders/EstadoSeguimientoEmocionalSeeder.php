<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoSeguimientoEmocional;

class EstadoSeguimientoEmocionalSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente',      'color' => 'secondary'],
            ['nombre' => 'En seguimiento', 'color' => 'primary'],
            ['nombre' => 'Derivado',       'color' => 'warning'],
            ['nombre' => 'Cerrado',        'color' => 'success'],
        ];

        foreach ($estados as $estado) {
            EstadoSeguimientoEmocional::firstOrCreate(
                ['nombre' => $estado['nombre']],
                ['color' => $estado['color']]
            );
        }
    }
}
