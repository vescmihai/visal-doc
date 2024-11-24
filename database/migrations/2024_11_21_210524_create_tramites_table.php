<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nombre del trámite
            $table->string('status')->default('Pendiente'); // Estado del trámite (Pendiente, Completado)
            $table->foreignId('user_id') // Columna que relaciona el trámite con el usuario
                  ->constrained() // Esto hace que se refiera a la tabla 'users'
                  ->onDelete('cascade'); // Si un usuario es eliminado, sus trámites también serán eliminados
            $table->text('observation')->nullable(); // Columna de observación, puede ser nula
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
