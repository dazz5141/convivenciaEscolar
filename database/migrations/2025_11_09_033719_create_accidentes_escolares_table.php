<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accidentes_escolares', function (Blueprint $table) {
            $table->id();

            $table->dateTime('fecha');

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('tipo_accidente_id');
            $table->unsignedBigInteger('establecimiento_id');

            $table->string('lugar')->nullable();
            $table->text('descripcion')->nullable();

            $table->text('atencion_inmediata')->nullable();

            $table->boolean('derivacion_salud')->default(0);

            $table->unsignedBigInteger('registrado_por'); // funcionario

            $table->timestamps();

            // FKs
            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_accidente_id')->references('id')->on('tipos_accidente')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('registrado_por')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accidentes_escolares');
    }
};
