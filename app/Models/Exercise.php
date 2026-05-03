<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * Relación: Un ejercicio pertenece a una máquina.
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Relación: Un ejercicio puede estar en muchos ejercicios seleccionados.
     */
    public function ejerciciosSeleccionados()
    {
        return $this->hasMany(EjercicioSeleccionado::class);
    }
}
