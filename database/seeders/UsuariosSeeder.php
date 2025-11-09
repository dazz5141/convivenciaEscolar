<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@colegio.cl',
                'password' => bcrypt('admin123'),
                'rol_id' => 1,
                'funcionario_id' => 1,
                'activo' => 1,
            ],
            [
                'email' => 'inspector@colegio.cl',
                'password' => bcrypt('inspector123'),
                'rol_id' => 4,
                'funcionario_id' => 2,
                'activo' => 1,
            ],
            [
                'email' => 'profesor@colegio.cl',
                'password' => bcrypt('profe123'),
                'rol_id' => 5,
                'funcionario_id' => 3,
                'activo' => 1,
            ],
        ];

        foreach ($users as $u) {
            Usuario::firstOrCreate(['email' => $u['email']], $u);
        }
    }
}
