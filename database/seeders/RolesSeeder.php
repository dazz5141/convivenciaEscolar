<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Administrador General',   // controla todo
            'Administrador Establecimiento', 
            'Inspector General',
            'Inspector',
            'Profesor',
            'Psicólogo',
            'Asistente de Aula',
            'Encargado Convivencia',
            'UTP'
        ];

        foreach ($roles as $r) {
            Rol::firstOrCreate(['nombre' => $r]);
        }
    }
}
