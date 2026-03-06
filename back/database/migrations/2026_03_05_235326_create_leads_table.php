<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');      // Nombre y Apellido
            $table->string('dni')->unique(); // DNI (único para evitar duplicados)
            $table->string('empresa');     // Panadería/Lugar de trabajo
            $table->string('telefono');    // WhatsApp o Celular
            $table->string('email')->nullable(); // <-- Agregamos esta línea
            $table->text('mensaje')->nullable(); // Alguna aclaración extra
            $table->boolean('contactado')->default(false); // Para que el administrativo sepa si ya lo llamó
            $table->timestamps();          // created_at y updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
