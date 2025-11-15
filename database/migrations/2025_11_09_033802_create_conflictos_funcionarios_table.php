<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conflictos_funcionarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('establecimiento_id');

            $table->date('fecha')->nullable();

            // Funcionarios involucrados
            $table->unsignedBigInteger('funcionario_1_id');
            $table->unsignedBigInteger('funcionario_2_id');

            // Quien registra el caso
            $table->unsignedBigInteger('registrado_por_id');

            $table->string('tipo_conflicto', 100)->nullable();
            $table->string('lugar_conflicto', 150)->nullable();

            $table->text('descripcion')->nullable();
            $table->text('acuerdos_previos')->nullable();

            $table->unsignedBigInteger('estado_id')->nullable();
            $table->boolean('confidencial')->default(0);
            $table->boolean('derivado_ley_karin')->default(0);

            $table->timestamps();

            // FKs
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('funcionario_1_id')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('funcionario_2_id')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            // REGISTRADO POR → USUARIOS
            $table->foreign('registrado_por_id')->references('id')->on('usuarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('estado_id')->references('id')->on('estados_conflicto_funcionario')
                ->onUpdate('cascade')->onDelete('set null');

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conflictos_funcionarios');
    }
};
