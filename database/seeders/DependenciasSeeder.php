<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dependencia;

class DependenciasSeeder extends Seeder
{
    public function run(): void
    {
        $items = ['Municipal', 'Particular Subvencionado', 'Particular Pagado'];

        foreach ($items as $i) {
            Dependencia::firstOrCreate(['nombre' => $i]);
        }
    }
}
