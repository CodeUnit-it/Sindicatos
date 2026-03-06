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
    Schema::create('members', function (Blueprint $table) {
        $table->id();
        $table->string('numero_afiliado')->unique();
        $table->string('nombre');
        $table->string('dni')->unique(); 
        $table->string('cuil')->nullable(); 
        $table->string('email')->nullable(); // <--- AHORA SÍ ESTÁ EN UNA LÍNEA NUEVA
        $table->string('empresa_actual');
        $table->string('telefono')->nullable();
        $table->date('fecha_afiliacion');
        $table->string('estado')->default('activo');
        $table->timestamps();
    });
}
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
