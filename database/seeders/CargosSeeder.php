<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargosSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Profesor',
            'Inspector',
            'Psicólogo',
            'Asistente de Aula',
            'Directivo',
            'Administrativo'
        ];

        foreach ($items as $i) {
            Cargo::firstOrCreate(['nombre' => $i]);
        }
    }
}
