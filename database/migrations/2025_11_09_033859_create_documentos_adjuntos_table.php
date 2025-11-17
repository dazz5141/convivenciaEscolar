<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_adjuntos', function (Blueprint $table) {
            $table->id();

            // Relación polimórfica correcta
            $table->string('entidad_type', 120);
            $table->unsignedBigInteger('entidad_id');

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id')->nullable();

            // Archivo
            $table->string('nombre_archivo', 255)->nullable();
            $table->text('ruta_archivo')->nullable();

            // Subido por funcionario
            $table->unsignedBigInteger('subido_por');

            // Timestamps estándar
            $table->timestamps();

            // Índices
            $table->index(['entidad_type', 'entidad_id'], 'idx_docs_polimorfico');
            $table->index('establecimiento_id', 'idx_docs_est');

            // Foreign keys
            $table->foreign('subido_por')
                ->references('id')->on('funcionarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_adjuntos');
    }
};
