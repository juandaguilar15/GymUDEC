<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineDayExercise extends Model
{
    protected $table = 'routine_day_exercise';

    protected $fillable = [
        'id_rutina_dias',
        'id_ejercicio',
        'sets',
        'reps',
        'duration',
        'duration_unit',
        'rests',
        'rests_unit',
    ];

    public function routineTrainingDay()
    {
        return $this->belongsTo(RoutineTrainingDay::class, 'id_rutina_dias');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'id_ejercicio');
    }
}
