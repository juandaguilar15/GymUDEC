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
        Schema::table('ejercicios_seleccionados', function (Blueprint $table) {
            // Agregar columnas para series y repeticiones
            $table->integer('sets')->nullable()->comment('Número de series');
            $table->integer('reps')->nullable()->comment('Número de repeticiones por serie');
            
            // Cambiar descanso de string a integer
            $table->dropColumn('descanso');
            $table->integer('descanso')->nullable()->comment('Tiempo de descanso en segundos o minutos');
            $table->enum('descanso_unidad', ['segundos', 'minutos'])->default('segundos')->comment('Unidad del tiempo de descanso');
            
            // Hacer duracion_ejercicio opcional
            $table->string('duracion_ejercicio')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ejercicios_seleccionados', function (Blueprint $table) {
            $table->dropColumn(['sets', 'reps', 'descanso', 'descanso_unidad']);
            
            // Restaurar descanso como string
            $table->string('descanso')->nullable();
            $table->string('duracion_ejercicio')->change();
        });
    }
};
