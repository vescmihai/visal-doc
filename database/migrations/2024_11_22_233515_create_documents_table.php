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
            $table->unsignedBigInteger('user_id'); // Relación con el cliente
            $table->string('type'); // Tipo de documento: Nota de Remisión, Carnet de Identidad, etc.
            $table->string('file_path'); // Ruta al archivo subido
            $table->enum('status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente'); // Estado del documento
            $table->text('observation')->nullable(); // Observaciones del gestor
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
