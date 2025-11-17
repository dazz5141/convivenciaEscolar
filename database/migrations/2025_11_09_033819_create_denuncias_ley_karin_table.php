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

            // Multi-colegio
            $table->unsignedBigInteger('establecimiento_id');

            // Polimórfico (nullable)
            $table->string('conflictable_type', 120)->nullable();
            $table->unsignedBigInteger('conflictable_id')->nullable();

            // Usuario que registra
            $table->unsignedBigInteger('registrado_por_id');

            // Fecha denuncia
            $table->date('fecha_denuncia')->nullable();

            // Flags
            $table->boolean('es_victima')->default(0);
            $table->boolean('confidencial')->default(0);

            /* ============================================
               Relación con tipos de denuncia
            ============================================ */
            $table->unsignedBigInteger('tipo_denuncia_id');

            /* ============================================
               DATOS DEL DENUNCIANTE
            ============================================ */
            $table->string('denunciante_nombre', 255)->nullable();
            $table->string('denunciante_cargo', 255)->nullable();
            $table->string('denunciante_area', 255)->nullable();

            // 🔥 AGREGADO NUEVO
            $table->string('denunciante_rut', 20)->nullable();


            /* ============================================
               DATOS DE LA VÍCTIMA
            ============================================ */
            $table->string('victima_nombre', 255)->nullable();
            $table->string('victima_rut', 20)->nullable();
            $table->text('victima_direccion')->nullable();
            $table->string('victima_comuna', 100)->nullable();
            $table->string('victima_region', 100)->nullable();
            $table->string('victima_telefono', 20)->nullable();
            $table->string('victima_email', 150)->nullable();


            /* ============================================
               DATOS DEL DENUNCIADO
            ============================================ */
            $table->string('denunciado_nombre', 255)->nullable();
            $table->string('denunciado_cargo', 255)->nullable();
            $table->string('denunciado_area', 255)->nullable();

            // 🔥 AGREGADO NUEVO
            $table->string('denunciado_rut', 20)->nullable();


            /* ============================================
               INFORMACIÓN COMPLEMENTARIA
            ============================================ */
            $table->string('jerarquia', 50)->nullable();
            $table->boolean('es_jefatura_inmediata')->default(0);
            $table->boolean('trabaja_directamente')->default(0);
            $table->boolean('denunciante_informo_superior')->default(0);

            $table->text('descripcion')->nullable();

            $table->string('tiempo_afectacion', 120)->nullable();
            $table->text('individualizacion_agresores')->nullable();
            $table->text('testigos')->nullable();

            // Evidencias
            $table->boolean('evidencia_testigos')->default(0);
            $table->boolean('evidencia_correos')->default(0);
            $table->boolean('evidencia_fotos')->default(0);
            $table->boolean('evidencia_videos')->default(0);
            $table->boolean('evidencia_otros')->default(0);
            $table->text('evidencia_otros_detalle')->nullable();

            // Cierre
            $table->text('observaciones')->nullable();
            $table->date('fecha_firma')->nullable();

            $table->timestamps();

            // Índices
            $table->index(['conflictable_type', 'conflictable_id']);
            $table->index('establecimiento_id');

            // FKs
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('registrado_por_id')
                ->references('id')->on('funcionarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('tipo_denuncia_id')
                ->references('id')->on('tipos_denuncia_ley_karin')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncias_ley_karin');
    }
};
