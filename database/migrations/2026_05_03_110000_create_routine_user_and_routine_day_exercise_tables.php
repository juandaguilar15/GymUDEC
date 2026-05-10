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
        Schema::create('routine_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_rutina')->constrained('routines')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['id_usuario', 'id_rutina']);
        });

        Schema::create('routine_day_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rutina_dias')->constrained('routine_training_days')->cascadeOnDelete();
            $table->foreignId('id_ejercicio')->constrained('exercises')->cascadeOnDelete();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->integer('rests')->nullable();
            $table->enum('rests_unit', ['segundos', 'minutos'])->default('segundos');
            $table->timestamps();
            $table->unique(['id_rutina_dias', 'id_ejercicio']);
        });

        Schema::table('routines', function (Blueprint $table) {
            if (Schema::hasColumn('routines', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });

        Schema::dropIfExists('ejercicios_seleccionados');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('ejercicios_seleccionados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained('routines')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->string('duracion_ejercicio')->nullable();
            $table->string('day_name')->nullable();
            $table->integer('descanso')->nullable();
            $table->enum('descanso_unidad', ['segundos', 'minutos'])->nullable();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->timestamps();
        });

        Schema::table('routines', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        Schema::dropIfExists('routine_day_exercise');
        Schema::dropIfExists('routine_user');
    }
};
