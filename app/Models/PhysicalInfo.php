<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalInfo extends Model
{
    protected $table = 'physical_infos';
    
    protected $fillable = [
        'email',
        'age',
        'date_of_birth',
        'height',
        'gender',
        'weight',
        'condition',
        'recommendation',
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'height' => 'float',
        'weight' => 'float',
    ];
    
    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
