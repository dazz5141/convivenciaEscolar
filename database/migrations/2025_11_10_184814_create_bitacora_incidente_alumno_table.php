<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_incidente_alumno', function (Blueprint $table) {
            $table->id();

            // Incidente principal
            $table->unsignedBigInteger('incidente_id');

            // Alumno involucrado
            $table->unsignedBigInteger('alumno_id');

            // Rol del alumno dentro del incidente
            $table->enum('rol', ['victima', 'agresor', 'testigo'])
                  ->default('victima');

            // Curso del alumno al momento del incidente
            $table->unsignedBigInteger('curso_id')->nullable();

            // Observación o nota específica del alumno
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('incidente_id')
                ->references('id')->on('bitacora_incidentes')
                ->cascadeOnDelete();

            $table->foreign('alumno_id')
                ->references('id')->on('alumnos')
                ->restrictOnDelete();

            $table->foreign('curso_id')
                ->references('id')->on('cursos')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_incidente_alumno');
    }
};
