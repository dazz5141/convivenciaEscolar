<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('RBD', 20)->unique();
            $table->string('nombre', 255);
            $table->string('direccion', 255);

            $table->unsignedBigInteger('dependencia_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('provincia_id');
            $table->unsignedBigInteger('comuna_id');

            $table->boolean('activo')->default(1);

            $table->timestamps();

            $table->foreign('dependencia_id')->references('id')->on('dependencias')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('region_id')->references('id')->on('regiones')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('provincia_id')->references('id')->on('provincias')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('comuna_id')->references('id')->on('comunas')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
