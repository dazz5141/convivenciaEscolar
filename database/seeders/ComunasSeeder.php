<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComunasSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/comunas.json');

        if (!file_exists($path)) {
            $this->command->error("No se encontró el archivo: $path");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!$data) {
            $this->command->error("Error parseando comunas.json");
            return;
        }

        foreach ($data as $provinciaId => $comunas) {
            foreach ($comunas as $nombre) {
                DB::table('comunas')->insert([
                    'nombre' => $nombre,
                    'provincia_id' => (int)$provinciaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Comunas cargadas correctamente.');
    }
}
