<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervenciones_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Relación con estudiante PIE
            $table->unsignedBigInteger('estudiante_pie_id');

            // Tipo de intervención (FK catálogo)
            $table->unsignedBigInteger('tipo_intervencion_id');

            // Profesional que realizó la intervención (FK profesionales_pie)
            $table->unsignedBigInteger('profesional_id');

            $table->date('fecha');
            $table->text('detalle')->nullable();

            $table->timestamps();

            // Índices
            $table->index('establecimiento_id', 'idx_intpie_establecimiento');
            $table->index('estudiante_pie_id', 'idx_intpie_estudiante');
            $table->index('profesional_id', 'idx_intpie_profesional');

            // FKs
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('estudiante_pie_id')
                ->references('id')->on('estudiantes_pie')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('tipo_intervencion_id')
                ->references('id')->on('tipos_intervencion_pie')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('profesional_id')
                ->references('id')->on('profesionales_pie')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervenciones_pie');
    }
};
