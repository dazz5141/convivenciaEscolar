<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes_individuales_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Estudiante PIE asociado
            $table->unsignedBigInteger('estudiante_pie_id');

            $table->date('fecha_inicio');
            $table->date('fecha_termino')->nullable();

            $table->text('objetivos')->nullable();
            $table->text('evaluacion')->nullable();

            $table->timestamps();

            // Índices
            $table->index('establecimiento_id', 'idx_planpie_establecimiento');
            $table->index('estudiante_pie_id', 'idx_planpie_estudiante');

            // FKs
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('estudiante_pie_id')
                ->references('id')->on('estudiantes_pie')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes_individuales_pie');
    }
};
