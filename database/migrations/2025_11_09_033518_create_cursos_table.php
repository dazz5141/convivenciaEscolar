<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('anio');     // Año académico
            $table->string('nivel', 50);      // Ej: "1° Básico"
            $table->string('letra', 5);       // Ej: "A"

            $table->unsignedBigInteger('establecimiento_id');

            $table->boolean('activo')->default(1);
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            // Un curso es único por colegio + año + nivel + letra
            $table->unique(['establecimiento_id', 'anio', 'nivel', 'letra']);

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
