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
        Schema::create('physical_infos', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea al email del usuario
            $table->string('email')->unique();
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            
            // Información física obligatoria
            $table->integer('age'); // Edad
            $table->date('date_of_birth'); // Fecha de nacimiento
            $table->decimal('height', 5, 2); // Altura en metros (ej: 1.75)
            $table->enum('gender', ['masculino', 'femenino', 'otro']); // Género
            $table->decimal('weight', 5, 2); // Peso en kg
            
            // Información opcional
            $table->text('condition')->nullable(); // Condición médica/física
            $table->text('recommendation')->nullable(); // Recomendaciones
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_infos');
    }
};
