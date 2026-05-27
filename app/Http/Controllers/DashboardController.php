<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PhysicalInfo;
use App\Models\Notice;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard correspondiente según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        $notices = Notice::where('is_active', true)->latest()->get();
        
        // Para estudiantes: verificar si tienen información física registrada
        if ($user->role === 'estudiante') {
            $physicalInfo = PhysicalInfo::where('email', $user->email)->first();
            
            // Si no tiene información física, mostrar mensaje para registrarse
            if (!$physicalInfo) {
                return view('student.register-physical-info', [
                    'user' => $user,
                ]);
            }

            $routineCount = $user->routines()->count();
            $canCreate = $physicalInfo->permisos === 'libre';
            $permisos = $physicalInfo->permisos;

            return view('student.dashboard', [
                'user' => $user,
                'notices' => $notices,
                'routineCount' => $routineCount,
                'canCreate' => $canCreate,
                'permisos' => $permisos,
            ]);
        }

        if ($user->role === 'enfermero') {
            $studentCount = User::where('role', 'estudiante')->count();
            $physicalInfoCount = PhysicalInfo::count();
            $updatedToday = PhysicalInfo::whereDate('updated_at', now()->toDateString())->count();

            return view('dashboard', [
                'user' => $user,
                'notices' => $notices,
                'stats' => [
                    'totalStudents' => $studentCount,
                    'totalPhysicalInfos' => $physicalInfoCount,
                    'updatedToday' => $updatedToday,
                ],
                'studentCount' => $studentCount,
            ]);
        }

        if ($user->role === 'administrador') {
            // En lugar de redirigir, llamamos directamente al método del AdminController 
            // o mantenemos la redirección pero aseguramos que admin.index es el único destino.
            return redirect()->route('admin.index');
        }
        
        // Retornamos el dashboard principal
        return view('dashboard', [
            'user' => $user,
            'notices' => $notices,
        ]);
    }
}