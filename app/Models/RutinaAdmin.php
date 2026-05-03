<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutinaAdmin extends Model
{
    protected $table = 'rutina_admin';

    protected $fillable = [
        'routine_id',
        'routine_name',
        'student_name',
        'student_email',
    ];

    public function routine()
    {
        return $this->belongsTo(Routine::class);
    }
}
