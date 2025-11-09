<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoCumplimiento;

class EstadosCumplimientoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'En curso',
            'Completada',
            'Incumplida'
        ];

        foreach ($items as $i) {
            EstadoCumplimiento::firstOrCreate(['nombre' => $i]);
        }
    }
}
