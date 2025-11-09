<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medidas_restaurativas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('incidente_id');
            $table->unsignedBigInteger('establecimiento_id');

            $table->unsignedBigInteger('tipo_medida_id');
            $table->unsignedBigInteger('responsable_funcionario'); // funcionario encargado

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->unsignedBigInteger('cumplimiento_estado_id');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('incidente_id')->references('id')->on('bitacora_incidentes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_medida_id')->references('id')->on('tipos_medida_restaurativa')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('responsable_funcionario')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('cumplimiento_estado_id')->references('id')->on('estados_cumplimiento')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índices recomendados
            $table->index(['incidente_id', 'tipo_medida_id']);
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidas_restaurativas');
    }
};
