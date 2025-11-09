<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesionales_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Profesional y tipo (FKs)
            $table->unsignedBigInteger('funcionario_id');
            $table->unsignedBigInteger('tipo_id'); // tipos_profesional_pie

            // Timestamps estándar Laravel
            $table->timestamps();

            // Índices lógicos
            $table->index('establecimiento_id', 'idx_pie_prof_est');

            // UNIQUE para impedir duplicación por tipo
            $table->unique(['funcionario_id', 'tipo_id'], 'uq_prof_pie_func_tipo');

            // FK relaciones
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('funcionario_id')
                ->references('id')->on('funcionarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('tipo_id')
                ->references('id')->on('tipos_profesional_pie')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesionales_pie');
    }
};
