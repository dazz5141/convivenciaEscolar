<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();

            $table->string('run', 20)->unique();

            $table->string('nombre', 120);
            $table->string('apellido_paterno', 120);
            $table->string('apellido_materno', 120);

            $table->unsignedBigInteger('curso_id');

            $table->unsignedBigInteger('sexo_id')->nullable();

            $table->string('telefono', 30)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();

            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_egreso')->nullable();

            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('comuna_id')->nullable();

            $table->boolean('activo')->default(1);

            $table->timestamps();

            $table->foreign('curso_id')->references('id')->on('cursos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('sexo_id')->references('id')->on('sexos')
                ->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('alumnos');
    }
};
