<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Exercise;
use App\Models\RoutineTrainingDay;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    /**
     * Mostrar lista de rutinas creadas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Routine::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('objective', 'like', "%$search%")
                  ->orWhere('level', 'like', "%$search%");
        }

        $routines = $query->paginate(15);

        return view('admin.gym.routines.index', [
            'routines' => $routines,
            'search' => $search,
        ]);
    }

    /**
     * Mostrar formulario para crear nueva rutina
     */
    public function create()
    {
        $exercises = Exercise::all();
        $days_of_week = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sábado' => 'Sábado',
            'domingo' => 'Domingo',
        ];

        return view('admin.gym.routines.create', [
            'exercises' => $exercises,
            'days_of_week' => $days_of_week,
        ]);
    }

    /**
     * Guardar nueva rutina
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:routines',
            'objective' => 'required|in:fuerza,cardio,mixto',
            'level' => 'required|string|max:100',
            'duration_weeks' => 'required|integer|min:1|max:52',
            'days_per_week' => 'required|integer|min:1|max:7',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:publica,privada',
            'training_days' => 'required|array|min:1',
            'training_days.*' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'exercises' => 'required|array|min:1',
            'exercises.*' => 'required|exists:exercises,id',
            'exercise_days' => 'required|array',
            'exercise_days.*' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'sets' => 'required|array',
            'sets.*' => 'nullable|integer|min:1',
            'reps' => 'required|array',
            'reps.*' => 'nullable|integer|min:1',
            'descansos' => 'required|array',
            'descansos.*' => 'nullable|integer|min:0',
            'descansos_unidad' => 'required|array',
            'descansos_unidad.*' => 'required|in:segundos,minutos',
        ]);

        // Crear la rutina sin user_id (es una rutina template del admin)
        $routine = Routine::create([
            'name' => $validated['name'],
            'objective' => $validated['objective'],
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'days_per_week' => $validated['days_per_week'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'user_id' => null, // Admin template, sin usuario específico
        ]);

        // Guardar días de entrenamiento
        foreach ($validated['training_days'] as $index => $day) {
            $routine->trainingDays()->create([
                'day_name' => $day,
                'day_order' => $index + 1,
            ]);
        }

        // Agregar ejercicios seleccionados con día, series, repeticiones y descanso
        foreach ($validated['exercises'] as $index => $exerciseId) {
            $routine->ejerciciosSeleccionados()->create([
                'exercise_id' => $exerciseId,
                'day_name' => $validated['exercise_days'][$index] ?? null,
                'sets' => $validated['sets'][$index] ?? null,
                'reps' => $validated['reps'][$index] ?? null,
                'descanso' => $validated['descansos'][$index] ?? null,
                'descanso_unidad' => $validated['descansos_unidad'][$index] ?? 'segundos',
                // user_id se deja null automáticamente por ser nullable
            ]);
        }

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$validated['name']}' creada exitosamente con " . count($validated['exercises']) . " ejercicio(s).");
    }

    /**
     * Mostrar formulario para editar rutina
     */
    public function edit($id)
    {
        $routine = Routine::findOrFail($id);
        $exercises = Exercise::all();
        $selectedExercises = $routine->ejerciciosSeleccionados;
        $trainingDays = $routine->trainingDays()->pluck('day_name')->toArray();
        
        $days_of_week = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sábado' => 'Sábado',
            'domingo' => 'Domingo',
        ];

        return view('admin.gym.routines.edit', [
            'routine' => $routine,
            'exercises' => $exercises,
            'selectedExercises' => $selectedExercises,
            'trainingDays' => $trainingDays,
            'days_of_week' => $days_of_week,
        ]);
    }

    /**
     * Actualizar rutina existente
     */
    public function update(Request $request, $id)
    {
        $routine = Routine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:routines,name,' . $routine->id,
            'objective' => 'required|in:fuerza,cardio,mixto',
            'level' => 'required|string|max:100',
            'duration_weeks' => 'required|integer|min:1|max:52',
            'days_per_week' => 'required|integer|min:1|max:7',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:publica,privada',
            'training_days' => 'required|array|min:1',
            'training_days.*' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'exercises' => 'required|array|min:1',
            'exercises.*' => 'required|exists:exercises,id',
            'exercise_days' => 'required|array',
            'exercise_days.*' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'sets' => 'required|array',
            'sets.*' => 'nullable|integer|min:1',
            'reps' => 'required|array',
            'reps.*' => 'nullable|integer|min:1',
            'descansos' => 'required|array',
            'descansos.*' => 'nullable|integer|min:0',
            'descansos_unidad' => 'required|array',
            'descansos_unidad.*' => 'required|in:segundos,minutos',
        ]);

        // Actualizar rutina
        $routine->update([
            'name' => $validated['name'],
            'objective' => $validated['objective'],
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'days_per_week' => $validated['days_per_week'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        // Remover días de entrenamiento anteriores
        $routine->trainingDays()->delete();

        // Guardar nuevos días de entrenamiento
        foreach ($validated['training_days'] as $index => $day) {
            $routine->trainingDays()->create([
                'day_name' => $day,
                'day_order' => $index + 1,
            ]);
        }

        // Remover ejercicios anteriores
        $routine->ejerciciosSeleccionados()->delete();

        // Agregar nuevos ejercicios con día, series, repeticiones y descanso
        foreach ($validated['exercises'] as $index => $exerciseId) {
            $routine->ejerciciosSeleccionados()->create([
                'exercise_id' => $exerciseId,
                'day_name' => $validated['exercise_days'][$index] ?? null,
                'sets' => $validated['sets'][$index] ?? null,
                'reps' => $validated['reps'][$index] ?? null,
                'descanso' => $validated['descansos'][$index] ?? null,
                'descanso_unidad' => $validated['descansos_unidad'][$index] ?? 'segundos',
                // user_id se deja null automáticamente por ser nullable
            ]);
        }

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$validated['name']}' actualizada exitosamente.");
    }

    /**
     * Eliminar rutina
     */
    public function destroy($id)
    {
        $routine = Routine::findOrFail($id);
        $routineName = $routine->name;

        // Los ejercicios seleccionados se eliminarán automáticamente por cascade
        $routine->delete();

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$routineName}' eliminada exitosamente.");
    }
}
