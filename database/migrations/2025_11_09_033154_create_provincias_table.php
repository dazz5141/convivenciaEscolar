<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provincias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->string('nombre', 150);

            $table->foreign('region_id')
                ->references('id')->on('regiones')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unique(['region_id', 'nombre']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provincias');
    }
};
