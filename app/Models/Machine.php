<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * Accesor para obtener la URL correcta de la imagen.
     * Soporta tanto URLs externas como archivos locales.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;
        
        // Si ya es una URL válida (http/https), la devolvemos tal cual
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : Storage::url($value);
    }

    /**
     * Relación: Una máquina tiene muchos ejercicios.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
