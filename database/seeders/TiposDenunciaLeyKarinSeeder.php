<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDenunciaLeyKarin;

class TiposDenunciaLeyKarinSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Acoso Laboral',
            'Maltrato',
            'Violencia de Género',
            'Acoso Sexual',
            'Trato Degradante',
            'Hostigamiento'
        ];

        foreach ($items as $i) {
            TipoDenunciaLeyKarin::firstOrCreate(['nombre' => $i]);
        }
    }
}
