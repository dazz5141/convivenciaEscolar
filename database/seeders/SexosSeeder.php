<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sexo;

class SexosSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Masculino', 'Femenino'] as $s) {
            Sexo::firstOrCreate(['nombre' => $s]);
        }
    }
}
