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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Ej: "CCT 231/94", "Formulario de Afiliación"
        $table->string('file_path'); // La ruta del PDF en el storage
        
        // El "type" es clave para separar Convenios de Formularios en la landing
        $table->enum('type', ['convenio', 'escala', 'formulario', 'otro'])->default('otro');
        
        $table->boolean('is_public')->default(true); // Para futuros usos, si queremos ocultar algo sin borrarlo
        $table->date('published_at')->nullable(); // Fecha oficial del documento
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
