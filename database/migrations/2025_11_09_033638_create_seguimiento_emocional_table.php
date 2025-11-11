<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimiento_emocional', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('establecimiento_id');
            $table->date('fecha');

            $table->unsignedBigInteger('nivel_emocional_id')->nullable();

            // Estado del seguimiento emocional
            $table->unsignedBigInteger('estado_id')->default(1);

            $table->text('comentario')->nullable();

            $table->unsignedBigInteger('evaluado_por'); // funcionario

            $table->timestamps();

            // FK
            $table->foreign('alumno_id')
                ->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('nivel_emocional_id')
                ->references('id')->on('niveles_emocionales')
                ->onUpdate('cascade')->onDelete('set null');

            // FK de estados
            $table->foreign('estado_id')
                ->references('id')->on('estados_seguimiento_emocional')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('evaluado_por')
                ->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índices clave para reportes
            $table->index(['alumno_id', 'fecha']);
            $table->index('establecimiento_id');
            $table->index('estado_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimiento_emocional');
    }
};
