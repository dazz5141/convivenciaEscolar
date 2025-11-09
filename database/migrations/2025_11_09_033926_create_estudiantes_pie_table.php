<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes_pie', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Alumno asociado al PIE
            $table->unsignedBigInteger('alumno_id');

            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable();

            $table->string('diagnostico', 255)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Índices
            $table->index('establecimiento_id', 'idx_est_pie_establecimiento');
            $table->unique('alumno_id', 'uq_est_pie_alumno'); // Un alumno solo puede estar 1 vez en PIE

            // FKs
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('alumno_id')
                ->references('id')->on('alumnos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes_pie');
    }
};
