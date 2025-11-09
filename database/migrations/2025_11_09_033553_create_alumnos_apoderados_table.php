<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos_apoderados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('apoderado_id');

            $table->string('tipo', 60)->nullable(); // Padre, madre, tutor, etc.

            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('apoderado_id')->references('id')->on('apoderados')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['alumno_id', 'apoderado_id', 'tipo']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos_apoderados');
    }
};
