<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Establecimiento;

class EstablecimientosSeeder extends Seeder
{
    public function run(): void
    {
        // Región Metropolitana = ID 7
        $region = DB::table('regiones')->where('id', 7)->first();

        // Provincia Santiago = ID 22
        $provincia = DB::table('provincias')->where('id', 22)->first();

        // Buscar comuna Santiago EXACTA dentro de la provincia 22
        $comuna = DB::table('comunas')
                    ->where('provincia_id', 22)
                    ->where('nombre', 'Santiago')
                    ->first();

        // Dependencia por defecto (asumo id 1)
        $dependencia = DB::table('dependencias')->where('id', 1)->first();

        // Validar todo
        if (!$region || !$provincia || !$comuna || !$dependencia) {
            $this->command->warn("No se pudo crear el establecimiento porque faltan datos base.");
            return;
        }

        Establecimiento::firstOrCreate(
            ['RBD' => '12345'],
            [
                'nombre' => 'Colegio de Ejemplo',
                'direccion' => 'Av. Central 123',
                'dependencia_id' => $dependencia->id,
                'region_id' => $region->id,
                'provincia_id' => $provincia->id,
                'comuna_id' => $comuna->id,
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
