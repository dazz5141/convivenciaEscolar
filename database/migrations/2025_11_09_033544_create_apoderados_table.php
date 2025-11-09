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

            $table->boolean('activo')->default(1);

            $table->timestamp('creado_en')->nullable()->useCurrent();

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apoderados');
    }
};
