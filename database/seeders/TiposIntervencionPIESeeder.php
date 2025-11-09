<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoIntervencionPIE;

class TiposIntervencionPIESeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Apoyo pedagógico',
            'Reforzamiento',
            'Evaluación diagnóstica',
            'Taller cognitivo',
            'Intervención individual'
        ];

        foreach ($items as $i) {
            TipoIntervencionPIE::firstOrCreate(['nombre' => $i]);
        }
    }
}
