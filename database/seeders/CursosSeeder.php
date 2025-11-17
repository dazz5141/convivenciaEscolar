<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosSeeder extends Seeder
{
    public function run(): void
    {
        $establecimientoId = 1;

        // Año actual y próximo año académico
        $anios = [
            date('Y')
        ];

        // Niveles de enseñanza básica y media
        $niveles = [
            // Básica
            '1° Básico', '2° Básico', '3° Básico', '4° Básico',
            '5° Básico', '6° Básico', '7° Básico', '8° Básico',

            // Media
            '1° Medio', '2° Medio', '3° Medio', '4° Medio',
        ];

        // Paralelos del curso
        $letras = ['A']; // se puede agregar B, C si el colegio tiene más cursos

        foreach ($anios as $anio) {
            foreach ($niveles as $nivel) {
                foreach ($letras as $letra) {

                    Curso::firstOrCreate([
                        'anio'               => $anio,
                        'nivel'              => $nivel,
                        'letra'              => $letra,
                        'establecimiento_id' => $establecimientoId,
                    ]);
                }
            }
        }
    }
}
