<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conflictos_apoderados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('establecimiento_id');

            $table->date('fecha')->nullable();

            // Funcionario que recibe el conflicto
            $table->unsignedBigInteger('funcionario_id');

            // Quien registra el caso (inspector, UTP, dirección, etc.)
            $table->unsignedBigInteger('registrado_por_id');

            // Apoderado interno (si existe en la BD)
            $table->unsignedBigInteger('apoderado_id')->nullable();

            // Apoderado externo (si se ingresa manualmente)
            $table->string('apoderado_nombre', 255)->nullable();
            $table->string('apoderado_rut', 20)->nullable();

            // Tipo de conflicto
            $table->string('tipo_conflicto', 100)->nullable();

            // Lugar donde ocurrió
            $table->string('lugar_conflicto', 150)->nullable();

            $table->text('descripcion')->nullable();
            $table->text('accion_tomada')->nullable();

            // Estado del conflicto
            $table->unsignedBigInteger('estado_id')->nullable();

            // Indicadores
            $table->boolean('confidencial')->default(0);
            $table->boolean('derivado_ley_karin')->default(0);

            $table->timestamps();

            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('funcionario_id')
                ->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('registrado_por_id')
                ->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('apoderado_id')
                ->references('id')->on('apoderados')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('estado_id')
                ->references('id')->on('estados_conflicto_apoderado')
                ->onUpdate('cascade')->onDelete('set null');

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conflictos_apoderados');
    }
};
