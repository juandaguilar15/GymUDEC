<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PhysicalInfo;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard correspondiente según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Para estudiantes: verificar si tienen información física registrada
        if ($user->role === 'estudiante') {
            $physicalInfo = PhysicalInfo::where('email', $user->email)->first();
            
            // Si no tiene información física, mostrar mensaje para registrarse
            if (!$physicalInfo) {
                return view('student.register-physical-info', [
                    'user' => $user,
                ]);
            }
        }
        
        // Retornamos el dashboard principal
        return view('dashboard', [
            'user' => $user,
        ]);
    }
}