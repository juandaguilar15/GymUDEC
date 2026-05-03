<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'type',
        'image_url',
        'status',
    ];

    /**
     * Tipos de datos para los atributos.
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relación: Una máquina tiene muchos ejercicios.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Relación: Una máquina tiene muchas rutinas.
     */
    public function routines()
    {
        return $this->hasMany(Routine::class);
    }
}
