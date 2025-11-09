<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoConflictoFuncionario;

class EstadosConflictoFuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Abierto',
            'Investigación',
            'Derivado',
            'Cerrado'
        ];

        foreach ($items as $i) {
            EstadoConflictoFuncionario::firstOrCreate(['nombre' => $i]);
        }
    }
}

