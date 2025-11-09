<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novedades_inspectoria', function (Blueprint $table) {
            $table->id();

            $table->dateTime('fecha');

            $table->unsignedBigInteger('funcionario_id');
            $table->unsignedBigInteger('alumno_id')->nullable();
            $table->unsignedBigInteger('curso_id')->nullable();

            $table->unsignedBigInteger('establecimiento_id');

            $table->unsignedBigInteger('tipo_novedad_id');
            $table->text('descripcion');

            $table->timestamps();

            // FKs
            $table->foreign('funcionario_id')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('curso_id')->references('id')->on('cursos')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_novedad_id')->references('id')->on('tipos_novedad')
                ->onUpdate('cascade')->onDelete('restrict');

            // Index para dashboards
            $table->index('establecimiento_id');
            $table->index(['fecha', 'tipo_novedad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novedades_inspectoria');
    }
};
