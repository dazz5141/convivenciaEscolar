<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denuncias_ley_karin', function (Blueprint $table) {
            $table->id();

            // Multicolegio
            $table->unsignedBigInteger('establecimiento_id');

            // Polimórfico: conflicto_apoderado o conflicto_funcionario
            $table->string('conflictable_type', 120);
            $table->unsignedBigInteger('conflictable_id');

            // Flags
            $table->boolean('es_victima')->nullable();
            $table->boolean('confidencial')->nullable();

            // Tipos
            $table->string('tipo_acoso', 120)->nullable();
            $table->string('tipo_laboral', 120)->nullable();
            $table->string('tipo_violencia', 120)->nullable();

            // Datos del denunciante
            $table->string('denunciante_nombre', 255)->nullable();
            $table->string('denunciante_cargo', 255)->nullable();
            $table->string('denunciante_area', 255)->nullable();

            // Víctima
            $table->string('victima_nombre', 255)->nullable();
            $table->string('victima_rut', 20)->nullable();
            $table->text('victima_direccion')->nullable();
            $table->string('victima_comuna', 100)->nullable();
            $table->string('victima_region', 100)->nullable();
            $table->string('victima_telefono', 20)->nullable();
            $table->string('victima_email', 150)->nullable();

            // Denunciado
            $table->string('denunciado_nombre', 255)->nullable();
            $table->string('denunciado_cargo', 255)->nullable();
            $table->string('denunciado_area', 255)->nullable();

            // Jerarquía
            $table->string('jerarquia', 50)->nullable();
            $table->boolean('es_jefatura_inmediata')->nullable();
            $table->boolean('trabaja_directamente')->nullable();
            $table->boolean('denunciante_informo_superior')->nullable();

            // Narración
            $table->text('narracion')->nullable();
            $table->string('tiempo_afectacion', 120)->nullable();
            $table->text('individualizacion_agresores')->nullable();
            $table->text('testigos')->nullable();

            // Evidencias
            $table->boolean('evidencia_testigos')->nullable();
            $table->boolean('evidencia_correos')->nullable();
            $table->boolean('evidencia_fotos')->nullable();
            $table->boolean('evidencia_videos')->nullable();
            $table->boolean('evidencia_otros')->nullable();
            $table->text('evidencia_otros_detalle')->nullable();

            // Comentarios y cierre
            $table->text('observaciones')->nullable();
            $table->date('fecha_firma')->nullable();

            // Timestamps
            $table->timestamp('creado_en')->nullable()->useCurrent();

            // FKs
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            // Polimórfico
            $table->index(['conflictable_type', 'conflictable_id']);
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncias_ley_karin');
    }
};
