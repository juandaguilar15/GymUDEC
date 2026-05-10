<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineTrainingDay extends Model
{
    protected $table = 'routine_training_days';
    
    protected $fillable = [
        'routine_id',
        'day_name',
        'day_order',
    ];
    
    /**
     * Relación con Routine
     */
    public function routine()
    {
        return $this->belongsTo(Routine::class);
    }
    
    /**
     * Obtener ejercicios asignados para este día.
     */
    public function exercises()
    {
        return $this->hasMany(RoutineDayExercise::class, 'id_rutina_dias');
    }
}
