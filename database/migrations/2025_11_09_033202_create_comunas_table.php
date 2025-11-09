<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comunas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provincia_id');
            $table->string('nombre', 150);

            $table->foreign('provincia_id')
                ->references('id')->on('provincias')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unique(['provincia_id', 'nombre']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunas');
    }
};
