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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del ejercicio
            $table->enum('type', ['cardio', 'fuerza']); // Tipo de ejercicio
            $table->text('description'); // Descripción del ejercicio
            $table->string('muscle_group'); // Grupo muscular (pecho, espalda, piernas, etc)
            $table->foreignId('machine_id')->constrained('machines')->onDelete('cascade'); // Relación con máquinas
            $table->string('image_url')->nullable(); // Imagen que represente el ejercicio
            $table->string('media_url')->nullable(); // URL de contenido multimedia (video, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
