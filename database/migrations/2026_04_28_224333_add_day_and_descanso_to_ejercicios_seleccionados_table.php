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
            // Solo agregar day_name si descanso ya existe
            if (!Schema::hasColumn('ejercicios_seleccionados', 'day_name')) {
                $table->enum('day_name', [
                    'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'
                ])->nullable()->comment('Día de la semana para este ejercicio');
            }
            
            if (!Schema::hasColumn('ejercicios_seleccionados', 'descanso')) {
                $table->string('descanso')->nullable()->comment('Tiempo de descanso después del ejercicio (ej: 30 segundos, 1 minuto)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ejercicios_seleccionados', function (Blueprint $table) {
            if (Schema::hasColumn('ejercicios_seleccionados', 'day_name')) {
                $table->dropColumn('day_name');
            }
            if (Schema::hasColumn('ejercicios_seleccionados', 'descanso')) {
                $table->dropColumn('descanso');
            }
        });
    }
};
