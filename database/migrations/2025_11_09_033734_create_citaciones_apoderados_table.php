<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citaciones_apoderados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('apoderado_id')->nullable();
            $table->unsignedBigInteger('funcionario_id'); // quien cita
            $table->unsignedBigInteger('establecimiento_id');

            $table->dateTime('fecha_citacion');
            $table->text('motivo')->nullable();

            $table->unsignedBigInteger('estado_id'); // FK estados_citacion
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('apoderado_id')->references('id')->on('apoderados')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('funcionario_id')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('estado_id')->references('id')->on('estados_citacion')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índices útiles en reportes
            $table->index('establecimiento_id');
            $table->index(['fecha_citacion', 'estado_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citaciones_apoderados');
    }
};
