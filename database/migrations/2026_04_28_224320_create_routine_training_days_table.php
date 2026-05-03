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
        Schema::create('routine_training_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained('routines')->cascadeOnDelete();
            $table->enum('day_name', [
                'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'
            ]);
            $table->integer('day_order')->comment('Orden de los días (1-7)');
            $table->timestamps();
            
            // Evitar duplicados de rutina + día
            $table->unique(['routine_id', 'day_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_training_days');
    }
};
