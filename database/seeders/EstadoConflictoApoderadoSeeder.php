<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoConflictoApoderado;

class EstadoConflictoApoderadoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'En Conversación',
            'En Investigación',
            'Escalado a Dirección',
            'Derivado a Ley Karin',
            'Cerrado',
        ];

        foreach ($estados as $estado) {
            EstadoConflictoApoderado::firstOrCreate(['nombre' => $estado]);
        }
    }
}
