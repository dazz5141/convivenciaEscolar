<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoConflictoFuncionario;

class EstadoConflictoFuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'En Conversación',
            'En Investigación',
            'Derivado a Ley Karin',
            'Cerrado',
            'Archivado'
        ];

        foreach ($estados as $e) {
            EstadoConflictoFuncionario::firstOrCreate(['nombre' => $e]);
        }
    }
}
