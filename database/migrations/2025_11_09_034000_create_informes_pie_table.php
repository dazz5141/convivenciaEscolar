<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informes_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Estudiante PIE asociado
            $table->unsignedBigInteger('estudiante_pie_id');

            $table->date('fecha');
            $table->string('tipo', 120)->nullable(); // informe técnico, seguimiento, etc.
            $table->text('contenido')->nullable();

            $table->timestamps();

            // Índices
            $table->index('establecimiento_id', 'idx_infpie_establecimiento');
            $table->index('estudiante_pie_id', 'idx_infpie_estudiante');
            $table->index('tipo', 'idx_infpie_tipo');

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
        Schema::dropIfExists('informes_pie');
    }
};
