<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimiento_emocional_observaciones', function (Blueprint $table) {
            $table->id();

            // FK al seguimiento emocional
            $table->unsignedBigInteger('seguimiento_id');
            
            // Texto de la observación
            $table->text('observacion');

            // Funcionario que la agregó
            $table->unsignedBigInteger('agregado_por');

            // Fecha/hora en que se registra la observación
            $table->timestamp('fecha_observacion')->useCurrent();

            $table->timestamps();

            // Relaciones
            $table->foreign('seguimiento_id')
                ->references('id')->on('seguimiento_emocional')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('agregado_por')
                ->references('id')->on('funcionarios')
                ->onDelete('restrict')->onUpdate('cascade');

            // Índices útiles
            $table->index(['seguimiento_id', 'fecha_observacion'], 'seg_obs_seg_fec_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimiento_emocional_observaciones');
    }
};
