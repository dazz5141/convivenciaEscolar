<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Establecimiento;

class EstablecimientosSeeder extends Seeder
{
    public function run(): void
    {
        $provincia = DB::table('provincias')->where('nombre', 'Santiago')->first();
        $comuna = DB::table('comunas')->where('nombre', 'Santiago')->first();

        if (!$provincia || !$comuna) {
            $this->command->error("No existe provincia o comuna Santiago.");
            dd($provincia, $comuna);
            return;
        }

        Establecimiento::firstOrCreate([
            'RBD' => '12345',
            'nombre' => 'Colegio de Ejemplo',
            'direccion' => 'Av. Central 123',
            'dependencia_id' => 1,

            'region_id' => 13,
            'provincia_id' => $provincia->id,
            'comuna_id' => $comuna->id,

            'activo' => 1,
        ]);
    }
}
