<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoContrato;

class TiposContratoSeeder extends Seeder
{
    public function run(): void
    {
        $items = ['Titular', 'Contrata', 'Honorarios'];

        foreach ($items as $i) {
            TipoContrato::firstOrCreate(
                ['nombre' => $i],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
