<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('derivaciones_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Estudiante PIE al que aplica la derivación
            $table->unsignedBigInteger('estudiante_pie_id');

            $table->date('fecha');
            $table->string('destino', 150);   // interno / externo
            $table->text('motivo')->nullable();
            $table->string('estado', 60)->nullable(); // en curso, cerrada, etc.

            $table->timestamps();

            // Índices
            $table->index('establecimiento_id', 'idx_derpie_establecimiento');
            $table->index('estudiante_pie_id', 'idx_derpie_estudiante');
            $table->index('estado', 'idx_derpie_estado');

            // Foreign Keys
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
        Schema::dropIfExists('derivaciones_pie');
    }
};
