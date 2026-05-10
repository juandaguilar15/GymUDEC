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
        Schema::table('exercises', function (Blueprint $table) {
            if (! Schema::hasColumn('exercises', 'exercise_format')) {
                $table->enum('exercise_format', ['series_reps', 'duration'])->default('series_reps')->after('machine_id');
            }
        });

        Schema::table('routine_day_exercise', function (Blueprint $table) {
            if (! Schema::hasColumn('routine_day_exercise', 'duration')) {
                $table->integer('duration')->nullable()->after('reps');
            }
            if (! Schema::hasColumn('routine_day_exercise', 'duration_unit')) {
                $table->enum('duration_unit', ['segundos', 'minutos'])->nullable()->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routine_day_exercise', function (Blueprint $table) {
            if (Schema::hasColumn('routine_day_exercise', 'duration_unit')) {
                $table->dropColumn('duration_unit');
            }
            if (Schema::hasColumn('routine_day_exercise', 'duration')) {
                $table->dropColumn('duration');
            }
        });

        Schema::table('exercises', function (Blueprint $table) {
            if (Schema::hasColumn('exercises', 'exercise_format')) {
                $table->dropColumn('exercise_format');
            }
        });
    }
};
