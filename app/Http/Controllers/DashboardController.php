<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard correspondiente según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Retornamos la vista basada en el nombre del rol (estudiante, administrador, enfermero)
        // Esto asume que tienes las carpetas creadas en resources/views/
        if (view()->exists("{$user->role}.dashboard")) {
            return view("{$user->role}.dashboard");
        }

        return view('dashboard'); // Vista por defecto si no existe la específica
    }
}