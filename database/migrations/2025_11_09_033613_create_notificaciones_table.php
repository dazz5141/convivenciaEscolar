<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            $table->string('tipo');                // incidente, atraso, citación, derivación, etc.
            $table->text('mensaje');              // mensaje visible en la campana

            $table->unsignedBigInteger('usuario_id'); // usuario DESTINATARIO
            $table->unsignedBigInteger('origen_id')->nullable(); // id del origen (id incidente, atraso, etc.)
            $table->string('origen_model')->nullable(); // App\Models\Incidente, etc.

            $table->unsignedBigInteger('establecimiento_id')->nullable();

            $table->boolean('leida')->default(0);

            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
