<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoConflictoApoderado;

class EstadosConflictoApoderadoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Abierto',
            'En mediación',
            'Derivado',
            'Cerrado'
        ];

        foreach ($items as $i) {
            EstadoConflictoApoderado::firstOrCreate(['nombre' => $i]);
        }
    }
}
