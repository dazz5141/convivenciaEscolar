<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoProfesionalPIE;

class TiposProfesionalPIESeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Psicopedagogo',
            'Fonoaudiólogo',
            'Terapeuta Ocupacional',
            'Psicólogo',
            'Educador Diferencial'
        ];

        foreach ($items as $i) {
            TipoProfesionalPIE::firstOrCreate(['nombre' => $i]);
        }
    }
}
