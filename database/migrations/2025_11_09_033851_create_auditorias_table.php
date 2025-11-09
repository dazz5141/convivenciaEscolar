<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('establecimiento_id')->nullable();

            $table->string('accion', 60);      // create / update / delete / login
            $table->string('modulo', 120);     // Ej: Alumno, Bitácora Incidentes
            $table->text('detalle')->nullable();

            $table->timestamp('created_at')->nullable()->useCurrent();

            // Índices
            $table->index('modulo', 'idx_auditoria_modulo');
            $table->index('establecimiento_id', 'idx_aud_est');

            // FKs
            $table->foreign('usuario_id')
                  ->references('id')->on('usuarios')
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
        Schema::dropIfExists('auditorias');
    }
};
