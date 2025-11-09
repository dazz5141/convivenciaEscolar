<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos_habilitados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('establecimiento_id');
            $table->string('modulo', 120);    // ejemplo: 'accidentes_escolares'

            $table->boolean('activo')->default(1);

            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['establecimiento_id', 'modulo']);
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos_habilitados');
    }
};
