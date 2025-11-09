<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoCitacion;

class EstadosCitacionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Pendiente',
            'Realizada',
            'Reprogramada',
            'Inasistencia'
        ];

        foreach ($items as $i) {
            EstadoCitacion::firstOrCreate(['nombre' => $i]);
        }
    }
}
