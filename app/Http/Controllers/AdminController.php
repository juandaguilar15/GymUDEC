<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhysicalInfo;
use App\Models\Notice;
use App\Models\Machine;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    private function authorizeAdmin()
    {
        if (! auth()->check() || auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso denegado.');
        }
    }

    // Dashboard principal del admin
    public function index()
    {
        $this->authorizeAdmin();

        $stats = [
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('role', 'administrador')->count(),
            'totalStudents' => User::where('role', 'estudiante')->count(),
            'totalNurses' => User::where('role', 'enfermero')->count(),
            'totalPhysicalInfos' => PhysicalInfo::count(),
            'totalMachines' => Machine::count(),
            'totalExercises' => Exercise::count(),
            'activeToday' => User::whereDate('updated_at', Carbon::today())->count(),
            'systemSince' => User::oldest('created_at')->first()?->created_at->format('d/m/Y') ?? now()->format('d/m/Y'),
        ];
        
        // Obtener los avisos para la vista
        $notices = Notice::with('author')->latest()->paginate(10);
        
        // Últimos usuarios registrados
        $recentUsers = User::latest('created_at')->take(5)->get();
        
        // Últimos registros de info física
        $recentPhysicalInfo = PhysicalInfo::with('user')->latest('updated_at')->take(5)->get();
        
        // Distribución de roles
        $roleDistribution = [
            'estudiante' => $stats['totalStudents'],
            'enfermero' => $stats['totalNurses'],
            'administrador' => $stats['totalAdmins'],
        ];
        
        // Registros de info física por mes (últimos 6 meses)
        $physicalInfoByMonth = $this->getPhysicalInfoByMonth();
        
        return view('admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentPhysicalInfo' => $recentPhysicalInfo,
            'roleDistribution' => $roleDistribution,
            'physicalInfoByMonth' => $physicalInfoByMonth,
            'notices' => $notices,
        ]);
    }
    
    // Ver análisis de estudiantes (como enfermero)
    public function viewAnalytics(Request $request)
    {
        $this->authorizeAdmin();
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
                'physicalInfos' => collect(),
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
    
    // Calcular estadísticas generales de información física
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
        
        // Tendencia de pesos (últimos 30 días)
        $weightTrend = $this->getWeightTrend($physicalInfos);
        
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
            'weightTrend' => $weightTrend,
        ];
    }
    
    // Obtener tendencia de peso (últimos 30 días)
    private function getWeightTrend($physicalInfos)
    {
        $trend = [];
        
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $avgWeight = $physicalInfos
                ->filter(fn($info) => $info->updated_at->toDateString() === $date)
                ->avg('weight');
            
            $trend[] = [
                'date' => Carbon::parse($date)->format('d/m'),
                'weight' => $avgWeight ? round($avgWeight, 2) : null,
            ];
        }
        
        return $trend;
    }
    
    // Listar usuarios con búsqueda y paginación
    public function listUsers(Request $request)
    {
        $this->authorizeAdmin();
        $search = $request->input('search');
        $role = $request->input('role');
        
        $query = User::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        if ($role && in_array($role, ['estudiante', 'enfermero', 'administrador'])) {
            $query->where('role', $role);
        }
        
        $users = $query->paginate(15)->withQueryString();
        
        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ]);
    }
    
    // Cambiar rol de usuario
    public function updateUserRole(Request $request, $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->withErrors(['role' => 'No puedes cambiar tu propio rol desde este panel.']);
        }
        
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
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        
        if (auth()->id() === $user->id) {
            return back()->withErrors(['delete' => 'No puedes eliminar tu propia cuenta desde este panel.']);
        }

        $userName = $user->name;
        
        // Eliminar información física asociada
        PhysicalInfo::where('email', $user->email)->delete();
        
        $user->delete();
        
        return back()->with('success', "Usuario {$userName} y su información física han sido eliminados.");
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
