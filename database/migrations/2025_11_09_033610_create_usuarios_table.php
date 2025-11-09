<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('email', 255)->unique();
            $table->string('password', 255);

            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('funcionario_id')->nullable();

            // ✅ Establecimiento asociado (Multicolegio)
            $table->unsignedBigInteger('establecimiento_id')->nullable();

            $table->string('nombre', 120)->nullable();
            $table->string('apellido_paterno', 120)->nullable();
            $table->string('apellido_materno', 120)->nullable();

            $table->string('avatar', 255)->nullable();

            $table->boolean('activo')->default(1);

            $table->timestamps();

            // Relaciones
            $table->foreign('rol_id')
                ->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('funcionario_id')
                ->references('id')->on('funcionarios')
                ->onUpdate('cascade')->onDelete('set null');

            // Nueva FK para multicoelgio
            $table->foreign('establecimiento_id')
                ->references('id')->on('establecimientos')
                ->onUpdate('cascade')->onDelete('set null');

            // Índices
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
