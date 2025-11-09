<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosSeeder extends Seeder
{
    public function run(): void
    {
        $establecimientoId = 1;

        $cursos = [
            ['anio' => date('Y'), 'nivel' => '1° Básico', 'letra' => 'A'],
            ['anio' => date('Y'), 'nivel' => '2° Básico', 'letra' => 'A'],
            ['anio' => date('Y'), 'nivel' => '3° Básico', 'letra' => 'A'],
            ['anio' => date('Y'), 'nivel' => '4° Básico', 'letra' => 'A'],
        ];

        foreach ($cursos as $c) {
            Curso::firstOrCreate([
                'anio' => $c['anio'],
                'nivel' => $c['nivel'],
                'letra' => $c['letra'],
                'establecimiento_id' => $establecimientoId
            ]);
        }
    }
}

