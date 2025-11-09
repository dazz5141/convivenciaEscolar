<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoNovedad;

class TiposNovedadSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Conducta',
            'Convivencia',
            'Daños',
            'Acoso',
            'Bullying',
            'Otro'
        ];

        foreach ($items as $i) {
            TipoNovedad::firstOrCreate(['nombre' => $i]);
        }
    }
}
