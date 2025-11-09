<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoIncidente;

class EstadosIncidenteSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'En Revisión',
            'Derivado',
            'Cerrado'
        ];

        foreach ($estados as $e) {
            EstadoIncidente::firstOrCreate(['nombre' => $e]);
        }
    }
}
