<?php

namespace App\Http\Controllers;

use App\Models\PhysicalInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    private function authorizeAdmin()
    {
        if (! auth()->check() || !in_array(auth()->user()->role, ['administrador', 'enfermero'])) {
            abort(403, 'Acceso denegado.');
        }
    }

    // Mostrar página de análisis
    public function index(Request $request)
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
            return view('analytics.index', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'statistics' => null,
                'isEmpty' => true,
            ]);
        }
        
        // Calcular estadísticas
        $statistics = $this->calculateStatistics($physicalInfos);
        
        return view('analytics.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statistics' => $statistics,
            'isEmpty' => false,
            'physicalInfos' => $physicalInfos,
        ]);
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
    
    // Exportar datos a CSV
    public function exportCsv(Request $request)
    {
        $this->authorizeAdmin();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        $physicalInfos = PhysicalInfo::with('user')
            ->whereBetween('updated_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->get();
        
        $fileName = 'physical_info_' . now()->format('d-m-Y_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $callback = function() use ($physicalInfos) {
            $file = fopen('php://output', 'w');
            
            // Headers del CSV
            fputcsv($file, ['Nombre', 'Email', 'Edad', 'Peso (kg)', 'Altura (m)', 'Género', 'IMC', 'Condición', 'Recomendación', 'Fecha Actualización']);
            
            foreach ($physicalInfos as $info) {
                $imc = round($info->weight / ($info->height ** 2), 2);
                fputcsv($file, [
                    $info->user->name,
                    $info->email,
                    $info->age,
                    $info->weight,
                    $info->height,
                    ucfirst($info->gender),
                    $imc,
                    $info->condition ?? 'N/A',
                    $info->recommendation ?? 'N/A',
                    $info->updated_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
