<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'objective',
        'level',
        'duration_weeks',
        'days_per_week',
        'description',
        'user_id',
        'status',
    ];

    /**
     * Relación: Una rutina pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una rutina puede tener muchos ejercicios seleccionados.
     */
    public function ejerciciosSeleccionados()
    {
        return $this->hasMany(EjercicioSeleccionado::class);
    }

    /**
     * Relación: Una rutina puede tener muchas asignaciones de admin.
     */
    public function rutinaAdmin()
    {
        return $this->hasMany(RutinaAdmin::class);
    }

    /**
     * Relación: Una rutina tiene muchos días de entrenamiento.
     */
    public function trainingDays()
    {
        return $this->hasMany(RoutineTrainingDay::class)->orderBy('day_order');
    }
}
