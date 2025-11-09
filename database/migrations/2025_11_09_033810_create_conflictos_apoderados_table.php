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
            $table->unsignedBigInteger('funcionario_id'); // quien recibe el conflicto

            $table->string('apoderado_nombre', 255)->nullable();
            $table->string('apoderado_rut', 20)->nullable();

            $table->string('tipo_conflicto', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('accion_tomada')->nullable();

            $table->unsignedBigInteger('estado_id')->nullable();
            $table->boolean('confidencial')->default(0);
            $table->boolean('derivado_ley_karin')->default(0);

            $table->timestamps();

            // FKs
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('funcionario_id')->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('estado_id')->references('id')->on('estados_conflicto_apoderado')
                ->onUpdate('cascade')->onDelete('set null');

            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conflictos_apoderados');
    }
};
