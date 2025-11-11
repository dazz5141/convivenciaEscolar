<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_incidente_observaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('incidente_id');
            $table->text('observacion');
            $table->unsignedBigInteger('agregado_por'); // funcionario
            $table->timestamp('fecha_observacion')->useCurrent();

            $table->timestamps();

            // FKs
            $table->foreign('incidente_id')
                ->references('id')->on('bitacora_incidentes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('agregado_por')
                ->references('id')->on('funcionarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Índice corto (evitar errores por nombres largos)
            $table->index(['incidente_id', 'fecha_observacion'], 'idx_inc_obs_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_incidente_observaciones');
    }
};
