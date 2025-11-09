<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denuncia_ley_karin_tipo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('denuncia_id');
            $table->unsignedBigInteger('tipo_id');

            // FKs
            $table->foreign('denuncia_id')->references('id')->on('denuncias_ley_karin')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('tipo_id')->references('id')->on('tipos_denuncia_ley_karin')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->unique(['denuncia_id', 'tipo_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncia_ley_karin_tipo');
    }
};
