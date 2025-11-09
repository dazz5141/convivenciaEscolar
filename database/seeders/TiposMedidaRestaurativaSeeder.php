<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoMedidaRestaurativa;

class TiposMedidaRestaurativaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Compromiso',
            'Reparación del daño',
            'Trabajo comunitario',
            'Mediación',
            'Acuerdo escrito'
        ];

        foreach ($items as $i) {
            TipoMedidaRestaurativa::firstOrCreate(['nombre' => $i]);
        }
    }
}
