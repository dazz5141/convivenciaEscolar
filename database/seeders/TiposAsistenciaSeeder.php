<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoAsistencia;

class TiposAsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Atraso',
            'Inasistencia',
            'Justificada',
            'Retiro Anticipado'
        ];

        foreach ($items as $i) {
            TipoAsistencia::firstOrCreate(['nombre' => $i]);
        }
    }
}
