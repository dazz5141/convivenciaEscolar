<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoAccidente;

class TiposAccidenteSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Caída',
            'Golpe',
            'Corte',
            'Quemadura',
            'Contusión',
            'Otro'
        ];

        foreach ($items as $i) {
            TipoAccidente::firstOrCreate(['nombre' => $i]);
        }
    }
}
