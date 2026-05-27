<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
        'admin_id'
    ];

    /**
     * Relación: Un aviso fue creado por un administrador.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Etiqueta legible del tipo en español.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'info' => 'Información',
            'warning' => 'Advertencia',
            'success' => 'Éxito',
            'danger' => 'Peligro',
            default => ucfirst($this->type ?? ''),
        };
    }
}