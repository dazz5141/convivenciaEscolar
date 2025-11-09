<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();

            $table->string('run', 20)->unique();

            $table->string('nombre', 120);
            $table->string('apellido_paterno', 120);
            $table->string('apellido_materno', 120);

            $table->unsignedBigInteger('cargo_id');
            $table->unsignedBigInteger('tipo_contrato_id');
            $table->unsignedBigInteger('establecimiento_id');

            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('comuna_id')->nullable();

            $table->string('direccion', 255)->nullable();

            $table->boolean('activo')->default(1);

            $table->timestamps();

            $table->foreign('cargo_id')->references('id')->on('cargos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_contrato_id')->references('id')->on('tipo_contratos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('region_id')->references('id')->on('regiones')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('provincia_id')->references('id')->on('provincias')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('comuna_id')->references('id')->on('comunas')
                ->onUpdate('cascade')->onDelete('set null');

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
