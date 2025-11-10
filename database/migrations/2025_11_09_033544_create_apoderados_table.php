<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id();

            $table->string('run', 20)->unique();

            $table->string('nombre', 120);
            $table->string('apellido_paterno', 120);
            $table->string('apellido_materno', 120);

            $table->string('telefono', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('direccion', 255)->nullable();

            // Colegio al que pertenece el apoderado
            $table->unsignedBigInteger('establecimiento_id');

            // Ubicación territorial
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('comuna_id')->nullable();

            $table->boolean('activo')->default(1);
            $table->timestamps();

            // Relaciones
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('region_id')
                ->references('id')->on('regiones')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('provincia_id')
                ->references('id')->on('provincias')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('comuna_id')
                ->references('id')->on('comunas')
                ->onUpdate('cascade')->onDelete('set null');

            // Índices
            $table->index('activo');
            $table->index('establecimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apoderados');
    }
};
