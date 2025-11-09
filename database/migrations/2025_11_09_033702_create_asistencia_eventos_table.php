<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencia_eventos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('establecimiento_id');

            $table->date('fecha');
            $table->time('hora')->nullable();

            $table->unsignedBigInteger('tipo_id'); // FK tipos_asistencia
            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('registrado_por'); // funcionario

            $table->timestamps();

            // FKs
            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_id')->references('id')->on('tipos_asistencia')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('registrado_por')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índice único recomendado por inspectoría
            $table->unique(['alumno_id', 'fecha', 'tipo_id', 'hora']);

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia_eventos');
    }
};
