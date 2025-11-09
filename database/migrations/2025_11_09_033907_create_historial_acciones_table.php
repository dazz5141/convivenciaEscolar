<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_acciones', function (Blueprint $table) {
            $table->id();

            // Polimórfico
            $table->string('entidad', 120);
            $table->unsignedBigInteger('entidad_id');

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id')->nullable();

            // Autor de la acción
            $table->unsignedBigInteger('funcionario_id');

            // Tipo de acción
            $table->string('accion', 120);
            $table->dateTime('fecha');

            // Índices
            $table->index(['entidad', 'entidad_id'], 'idx_hist_polimorfico');
            $table->index('fecha', 'idx_hist_fecha');
            $table->index('establecimiento_id', 'idx_hist_est');

            // Timestamps Laravel
            $table->timestamps();

            // FKs
            $table->foreign('funcionario_id')
                ->references('id')->on('funcionarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_acciones');
    }
};
