<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_curso_historial', function (Blueprint $table) {
            $table->id();

            // Alumno afectado
            $table->unsignedBigInteger('alumno_id');

            // Curso al que fue asignado en ese momento
            $table->unsignedBigInteger('curso_id');

            // Colegio (para mantener integridad multicolegio)
            $table->unsignedBigInteger('establecimiento_id');

            // Datos del cambio
            $table->date('fecha_cambio')->default(now());
            $table->text('motivo')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('alumno_id')
                ->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('curso_id')
                ->references('id')->on('cursos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índices recomendados
            $table->index('alumno_id');
            $table->index('curso_id');
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno_curso_historial');
    }
};
