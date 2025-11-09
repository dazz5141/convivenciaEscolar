<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Funcionario;

class FuncionariosSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'run' => '11111111-1',
                'nombre' => 'Administrador',
                'apellido_paterno' => 'General',
                'apellido_materno' => 'Sistema',
                'cargo_id' => 5, // Directivo
                'tipo_contrato_id' => 1,
                'establecimiento_id' => 1,
                'activo' => 1,
            ],
            [
                'run' => '22222222-2',
                'nombre' => 'Juan',
                'apellido_paterno' => 'Inspector',
                'apellido_materno' => 'Pérez',
                'cargo_id' => 2, // Inspector
                'tipo_contrato_id' => 1,
                'establecimiento_id' => 1,
                'activo' => 1,
            ],
            [
                'run' => '33333333-3',
                'nombre' => 'María',
                'apellido_paterno' => 'Profesora',
                'apellido_materno' => 'González',
                'cargo_id' => 1, // Profesor
                'tipo_contrato_id' => 1,
                'establecimiento_id' => 1,
                'activo' => 1,
            ],
        ];

        foreach ($items as $i) {
            Funcionario::firstOrCreate(['run' => $i['run']], $i);
        }
    }
}
