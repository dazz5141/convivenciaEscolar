<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_incidentes', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('tipo_incidente', 120)->nullable();
            $table->text('descripcion')->nullable();

            $table->unsignedBigInteger('curso_id'); // curso del primer alumno
            $table->unsignedBigInteger('establecimiento_id');

            $table->text('accion_realizada')->nullable();

            $table->unsignedBigInteger('reportado_por'); // funcionario
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->unsignedBigInteger('seguimiento_id')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('curso_id')->references('id')->on('cursos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('reportado_por')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('estado_id')->references('id')->on('estados_incidente')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('seguimiento_id')->references('id')->on('seguimiento_emocional')
                ->onUpdate('cascade')->onDelete('set null');

            // Índices útiles
            $table->index('fecha');
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_incidentes');
    }
};
