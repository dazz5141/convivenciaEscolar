<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_seguimiento_emocional', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('color', 30)->nullable(); // Para badge opcional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_seguimiento_emocional');
    }
};
