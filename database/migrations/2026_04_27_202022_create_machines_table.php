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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la máquina
            $table->enum('type', ['cardio', 'fuerza', 'mixto']); // Tipo de máquina
            $table->string('image_url')->nullable(); // URL de la imagen
            $table->boolean('status')->default(true); // true = activa, false = inactiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
