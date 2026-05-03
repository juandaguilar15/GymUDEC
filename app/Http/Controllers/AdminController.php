<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhysicalInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard principal del admin
    public function index()
    {
        // Estadísticas generales
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'administrador')->count();
        $totalEstudiantes = User::where('role', 'estudiante')->count();
        $totalEnfermeros = User::where('role', 'enfermero')->count();
        $totalPhysicalInfo = PhysicalInfo::count();
        $usersActiveToday = User::whereDate('updated_at', Carbon::today())->count();
        $systemStartDate = User::oldest('created_at')->first()?->created_at ?? now();
        
        // Últimos usuarios registrados
        $recentUsers = User::latest('created_at')->take(5)->get();
        
        // Últimos registros de info física
        $recentPhysicalInfo = PhysicalInfo::with('user')->latest('updated_at')->take(5)->get();
        
        // Distribución de roles
        $roleDistribution = [
            'estudiante' => $totalEstudiantes,
            'enfermero' => $totalEnfermeros,
            'administrador' => $totalAdmins,
        ];
        
        // Registros de info física por mes (últimos 6 meses)
        $physicalInfoByMonth = $this->getPhysicalInfoByMonth();
        
        return view('admin.index', [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalEstudiantes' => $totalEstudiantes,
            'totalEnfermeros' => $totalEnfermeros,
            'totalPhysicalInfo' => $totalPhysicalInfo,
            'usersActiveToday' => $usersActiveToday,
            'systemStartDate' => $systemStartDate,
            'recentUsers' => $recentUsers,
            'recentPhysicalInfo' => $recentPhysicalInfo,
            'roleDistribution' => $roleDistribution,
            'physicalInfoByMonth' => $physicalInfoByMonth,
        ]);
    }
    
    // Ver análisis de estudiantes (como enfermero)
    public function viewAnalytics(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        // Validar fechas
        if ($startDate > $endDate) {
            return back()->withErrors(['date_range' => 'La fecha de inicio no puede ser mayor a la fecha de fin.']);
        }
        
        // Obtener registros en el rango de fechas
        $physicalInfos = PhysicalInfo::with('user')
            ->whereBetween('updated_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->get();
        
        if ($physicalInfos->isEmpty()) {
            return view('admin.analytics', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'statistics' => null,
                'isEmpty' => true,
            ]);
        }
        
        // Calcular estadísticas
        $statistics = $this->calculateStatistics($physicalInfos);
        
        return view('admin.analytics', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statistics' => $statistics,
            'isEmpty' => false,
            'physicalInfos' => $physicalInfos,
        ]);
    }
    
    // Listar usuarios con búsqueda y paginación
    public function listUsers(Request $request)
    {
        $search = $request->input('search');
        
        $query = User::query();
        
        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }
        
        $users = $query->paginate(15);
        
        return view('admin.users', [
            'users' => $users,
            'search' => $search,
        ]);
    }
    
    // Cambiar rol de usuario
    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'role' => 'required|in:estudiante,administrador,enfermero',
        ]);
        
        $oldRole = $user->role;
        $user->update($validated);
        
        return back()->with('success', "Rol de {$user->name} actualizado de {$oldRole} a {$validated['role']}.");
    }
    
    // Eliminar usuario
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        
        // Eliminar información física asociada
        PhysicalInfo::where('email', $user->email)->delete();
        
        $user->delete();
        
        return back()->with('success', "Usuario {$userName} y su información física han sido eliminados.");
    }
    
    // Calcular estadísticas generales
    private function calculateStatistics($physicalInfos)
    {
        $count = $physicalInfos->count();
        
        // Estadísticas básicas
        $avgAge = $physicalInfos->avg('age');
        $avgWeight = $physicalInfos->avg('weight');
        $avgHeight = $physicalInfos->avg('height');
        $avgImc = $physicalInfos->average(function ($info) {
            return $info->weight / ($info->height ** 2);
        });
        
        // Mín y máx
        $minWeight = $physicalInfos->min('weight');
        $maxWeight = $physicalInfos->max('weight');
        $minHeight = $physicalInfos->min('height');
        $maxHeight = $physicalInfos->max('height');
        $minAge = $physicalInfos->min('age');
        $maxAge = $physicalInfos->max('age');
        
        // Distribución de géneros
        $genderDistribution = $physicalInfos->groupBy('gender')->map(fn($group) => $group->count());
        
        // Peso por género
        $weightByGender = $physicalInfos->groupBy('gender')->map(fn($group) => round($group->avg('weight'), 2));
        $heightByGender = $physicalInfos->groupBy('gender')->map(fn($group) => round($group->avg('height'), 2));
        $ageByGender = $physicalInfos->groupBy('gender')->map(fn($group) => round($group->avg('age'), 2));
        
        // Categorías de IMC
        $imcCategories = [
            'bajo_peso' => 0,
            'normal' => 0,
            'sobrepeso' => 0,
            'obesidad' => 0,
        ];
        
        foreach ($physicalInfos as $info) {
            $imc = $info->weight / ($info->height ** 2);
            
            if ($imc < 18.5) {
                $imcCategories['bajo_peso']++;
            } elseif ($imc < 25) {
                $imcCategories['normal']++;
            } elseif ($imc < 30) {
                $imcCategories['sobrepeso']++;
            } else {
                $imcCategories['obesidad']++;
            }
        }
        
        // Rango de edad
        $ageRanges = [
            '15-20' => $physicalInfos->whereBetween('age', [15, 20])->count(),
            '21-25' => $physicalInfos->whereBetween('age', [21, 25])->count(),
            '26-30' => $physicalInfos->whereBetween('age', [26, 30])->count(),
            '31-40' => $physicalInfos->whereBetween('age', [31, 40])->count(),
            '40+' => $physicalInfos->where('age', '>=', 41)->count(),
        ];
        
        return [
            'count' => $count,
            'avgAge' => round($avgAge, 2),
            'avgWeight' => round($avgWeight, 2),
            'avgHeight' => round($avgHeight, 2),
            'avgImc' => round($avgImc, 2),
            'minWeight' => $minWeight,
            'maxWeight' => $maxWeight,
            'minHeight' => $minHeight,
            'maxHeight' => $maxHeight,
            'minAge' => $minAge,
            'maxAge' => $maxAge,
            'genderDistribution' => $genderDistribution,
            'weightByGender' => $weightByGender,
            'heightByGender' => $heightByGender,
            'ageByGender' => $ageByGender,
            'imcCategories' => $imcCategories,
            'ageRanges' => $ageRanges,
        ];
    }
    
    // Obtener registros de info física por mes
    private function getPhysicalInfoByMonth()
    {
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = PhysicalInfo::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $months[] = [
                'month' => $date->format('M/Y'),
                'count' => $count,
            ];
        }
        
        return $months;
    }
}
