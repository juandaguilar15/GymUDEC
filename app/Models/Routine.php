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
        'status',
    ];

    /**
     * Relación: Una rutina puede pertenecer a muchos usuarios.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'routine_user', 'id_rutina', 'id_usuario')
            ->withTimestamps();
    }

    /**
     * Relación: Una rutina tiene muchos días de entrenamiento.
     */
    public function trainingDays()
    {
        return $this->hasMany(RoutineTrainingDay::class)->orderBy('day_order');
    }

    /**
     * Relación: Todos los ejercicios asignados a los días de esta rutina.
     */
    public function dayExercises()
    {
        return $this->hasManyThrough(
            RoutineDayExercise::class,
            RoutineTrainingDay::class,
            'routine_id', // Foreign key on routine_training_days table...
            'id_rutina_dias', // Foreign key on routine_day_exercise table...
            'id',
            'id'
        );
    }

    /**
     * Determina si la rutina fue creada o asignada por un administrador.
     */
    public function isAdminCreated(): bool
    {
        if ($this->relationLoaded('users')) {
            return $this->users->contains('role', 'administrador');
        }

        return $this->users()->where('role', 'administrador')->exists();
    }

    /**
     * Obtiene la etiqueta del creador de la rutina.
     */
    public function getCreatorLabelAttribute(): string
    {
        return $this->isAdminCreated() ? 'Administrador' : 'Estudiante';
    }
}
