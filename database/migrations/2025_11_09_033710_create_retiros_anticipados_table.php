<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retiros_anticipados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('establecimiento_id');
            
            $table->date('fecha');
            $table->time('hora');

            $table->string('motivo', 255)->nullable();

            $table->unsignedBigInteger('apoderado_id')->nullable();  // quien retira

            // =====================================================
            // CAMPOS NUEVOS (MODO HÍBRIDO)
            // =====================================================
            $table->string('nombre_retira')->nullable();
            $table->string('run_retira', 20)->nullable();
            $table->string('parentesco_retira', 50)->nullable();
            $table->string('telefono_retira', 20)->nullable();
            // =====================================================

            $table->unsignedBigInteger('entregado_por');             // funcionario

            $table->text('observaciones')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('apoderado_id')->references('id')->on('apoderados')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('entregado_por')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retiros_anticipados');
    }
};
