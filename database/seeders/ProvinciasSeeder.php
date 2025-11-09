<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    public function run(): void
    {
        $provinciasPorRegion = [
            1 => ['Arica', 'Parinacota'],
            2 => ['Iquique', 'Tamarugal'],
            3 => ['Antofagasta', 'El Loa', 'Tocopilla'],
            4 => ['Chañaral', 'Copiapó', 'Huasco'],
            5 => ['Elqui', 'Limarí', 'Choapa'],
            6 => ['Valparaíso', 'Marga Marga', 'Quillota', 'San Antonio', 'Isla de Pascua', 'Los Andes', 'Petorca', 'San Felipe de Aconcagua'],
            7 => ['Santiago', 'Chacabuco', 'Cordillera', 'Maipo', 'Melipilla', 'Talagante'],
            8 => ['Cachapoal', 'Colchagua', 'Cardenal Caro'],
            9 => ['Curicó', 'Talca', 'Linares', 'Cauquenes'],
            10 => ['Diguillín', 'Itata', 'Punilla'],
            11 => ['Biobío', 'Concepción', 'Arauco'],
            12 => ['Cautín', 'Malleco'],
            13 => ['Valdivia', 'Ranco'],
            14 => ['Osorno', 'Llanquihue', 'Chiloé', 'Palena'],
            15 => ['Coyhaique', 'Aysén', 'General Carrera', 'Capitán Prat'],
            16 => ['Antártica Chilena', 'Magallanes', 'Tierra del Fuego', 'Última Esperanza'],
        ];

        foreach ($provinciasPorRegion as $regionId => $provincias) {
            foreach ($provincias as $nombre) {
                DB::table('provincias')->insert([
                    'nombre' => $nombre,
                    'region_id' => $regionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
