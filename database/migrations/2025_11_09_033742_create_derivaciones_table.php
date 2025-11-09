<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('derivaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('establecimiento_id');

            // Polimórfico: puede venir de bitácora o seguimiento emocional
            $table->string('entidad_type', 120)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();

            // Datos de la derivación
            $table->string('tipo', 50); // interna/external
            $table->string('destino', 150);
            $table->text('motivo')->nullable();
            $table->string('estado', 60)->nullable(); // Enviada, En revisión, Cerrada

            $table->date('fecha');

            $table->unsignedBigInteger('registrado_por'); // funcionario

            $table->timestamps();

            // FKs
            $table->foreign('alumno_id')->references('id')->on('alumnos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('registrado_por')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            // Índices
            $table->index(['entidad_type', 'entidad_id']);
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('derivaciones');
    }
};
