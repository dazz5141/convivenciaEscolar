<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NivelEmocional;

class NivelesEmocionalesSeeder extends Seeder
{
    public function run(): void
    {
        $niveles = [
            'Muy Bajo',
            'Bajo',
            'Medio',
            'Alto',
            'Muy Alto'
        ];

        foreach ($niveles as $n) {
            NivelEmocional::firstOrCreate(['nombre' => $n]);
        }
    }
}

