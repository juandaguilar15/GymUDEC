<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exercise extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'muscle_group',
        'machine_id',
        'image_url',
        'media_url',
        'exercise_format',
    ];

    /**
     * Accesor para la imagen del ejercicio.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : Storage::url($value);
    }

    /**
     * Accesor para el contenido multimedia (video).
     */
    public function getMediaUrlAttribute($value)
    {
        if (!$value) return null;
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : Storage::url($value);
    }

    /**
     * Relación: Un ejercicio pertenece a una máquina.
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Relación: Un ejercicio está asignado a muchos días de rutina.
     */
    public function routineDayExercises()
    {
        return $this->hasMany(RoutineDayExercise::class, 'id_ejercicio');
    }
}
