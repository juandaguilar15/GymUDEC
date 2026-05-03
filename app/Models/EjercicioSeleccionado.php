<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EjercicioSeleccionado extends Model
{
    protected $table = 'ejercicios_seleccionados';

    protected $fillable = [
        'routine_id',
        'user_id',
        'exercise_id',
        'duracion_ejercicio',
        'day_name',
        'descanso',
        'descanso_unidad',
        'sets',
        'reps',
    ];

    /**
     * Relación: Un ejercicio seleccionado pertenece a una rutina.
     */
    public function routine()
    {
        return $this->belongsTo(Routine::class);
    }

    /**
     * Relación: Un ejercicio seleccionado pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un ejercicio seleccionado pertenece a un ejercicio.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
