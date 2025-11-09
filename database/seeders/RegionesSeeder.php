<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionesSeeder extends Seeder
{
    public function run(): void
    {
        $regiones = [
            'Arica y Parinacota',
            'Tarapacá',
            'Antofagasta',
            'Atacama',
            'Coquimbo',
            'Valparaíso',
            'Metropolitana de Santiago',
            'O’Higgins',
            'Maule',
            'Ñuble',
            'Biobío',
            'La Araucanía',
            'Los Ríos',
            'Los Lagos',
            'Aysén',
            'Magallanes'
        ];

        foreach ($regiones as $r) {
            Region::firstOrCreate(['nombre' => $r]);
        }
    }
}
