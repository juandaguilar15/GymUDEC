<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Exercise;
use App\Models\RoutineDayExercise;
use App\Models\RoutineTrainingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoutineController extends Controller implements HasMiddleware
{
    /**
     * Configurar middleware para el controlador
     */
    public static function middleware(): array
    {
        return ['auth', 'role:administrador'];
    }

    /**
     * Verifica que el usuario actual sea administrador.
     */
    private function authorizeAdmin()
    {
        if (! auth()->check() || auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso denegado.');
        }
    }

    /**
     * Normaliza nombres de días recibidos en la petición para aceptar variantes sin tildes.
     */
    private function normalizeDayInputs(Request $request)
    {
        $map = [
            'lunes' => 'lunes',
            'martes' => 'martes',
            'miercoles' => 'miércoles',
            'mierc' => 'miércoles',
            'miércoles' => 'miércoles',
            'jueves' => 'jueves',
            'viernes' => 'viernes',
            'sabado' => 'sábado',
            'sábado' => 'sábado',
            'domingo' => 'domingo',
        ];

        $fields = ['training_days', 'exercise_days'];

        foreach ($fields as $field) {
            if (! $request->has($field)) {
                continue;
            }

            $items = $request->input($field);
            if (! is_array($items)) {
                continue;
            }

            $normalized = array_map(function ($val) use ($map) {
                $val = trim(mb_strtolower((string) $val));
                // reemplazar tildes y caracteres especiales a forma base
                $plain = strtr($val, [
                    'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                    'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
                    'ñ' => 'n', 'Ñ' => 'n'
                ]);

                return $map[$plain] ?? ($map[$val] ?? $val);
            }, $items);

            $request->merge([$field => $normalized]);
        }
    }

    /**
     * Mostrar lista de rutinas creadas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Routine::whereHas('users', function ($query) {
            $query->where('role', 'administrador');
        });

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('objective', 'like', "%$search%")
                      ->orWhere('level', 'like', "%$search%");
            });
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
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();
        $this->normalizeDayInputs($request);

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
            'durations' => 'nullable|array',
            'durations.*' => 'nullable|integer|min:1',
            'duration_units' => 'nullable|array',
            'duration_units.*' => 'nullable|in:segundos,minutos',
            'descansos' => 'required|array',
            'descansos.*' => 'nullable|integer|min:0',
            'descansos_unidad' => 'required|array',
            'descansos_unidad.*' => 'required|in:segundos,minutos',
        ]);

        $exerciseFormats = Exercise::whereIn('id', $validated['exercises'])
            ->pluck('exercise_format', 'id')
            ->toArray();

        $errors = [];
        foreach ($validated['exercises'] as $exIndex => $exerciseId) {
            $format = $exerciseFormats[$exerciseId] ?? 'series_reps';

            if (($validated['exercise_days'][$exIndex] ?? null) === null) {
                continue;
            }

            if ($format === 'duration') {
                if (empty($validated['durations'][$exIndex])) {
                    $errors["durations.{$exIndex}"] = 'La duración es requerida para ejercicios por duración.';
                }
                if (empty($validated['duration_units'][$exIndex])) {
                    $errors["duration_units.{$exIndex}"] = 'La unidad de tiempo es requerida para ejercicios por duración.';
                }
            } else {
                if (empty($validated['sets'][$exIndex])) {
                    $errors["sets.{$exIndex}"] = 'Las series son requeridas para ejercicios de series y repeticiones.';
                }
                if (empty($validated['reps'][$exIndex])) {
                    $errors["reps.{$exIndex}"] = 'Las repeticiones son requeridas para ejercicios de series y repeticiones.';
                }
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return DB::transaction(function () use ($validated, $exerciseFormats) {
            $routine = Routine::create([
                'name' => $validated['name'],
                'objective' => $validated['objective'],
                'level' => $validated['level'],
                'duration_weeks' => $validated['duration_weeks'],
                'days_per_week' => $validated['days_per_week'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            if (auth()->check()) {
                $routine->users()->sync([auth()->id()]);
            }

            foreach ($validated['training_days'] as $index => $day) {
                $routineDay = $routine->trainingDays()->create([
                    'day_name' => $day,
                    'day_order' => $index + 1,
                ]);

                foreach ($validated['exercises'] as $exIndex => $exerciseId) {
                    if (($validated['exercise_days'][$exIndex] ?? null) === $day) {
                        $format = $exerciseFormats[$exerciseId] ?? 'series_reps';

                        RoutineDayExercise::create([
                            'id_rutina_dias' => $routineDay->id,
                            'id_ejercicio' => $exerciseId,
                            'sets' => $format === 'series_reps' ? ($validated['sets'][$exIndex] ?? null) : null,
                            'reps' => $format === 'series_reps' ? ($validated['reps'][$exIndex] ?? null) : null,
                            'duration' => $format === 'duration' ? ($validated['durations'][$exIndex] ?? null) : null,
                            'duration_unit' => $format === 'duration' ? ($validated['duration_units'][$exIndex] ?? null) : null,
                            'rests' => $validated['descansos'][$exIndex] ?? null,
                            'rests_unit' => $validated['descansos_unidad'][$exIndex] ?? 'segundos',
                        ]);
                    }
                }
            }

            return redirect()->route('routines.index')
                ->with('success', "Rutina '{$validated['name']}' creada exitosamente.");
        });

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$validated['name']}' creada exitosamente con " . count($validated['exercises']) . " ejercicio(s).");
    }

    /**
     * Mostrar formulario para editar rutina
     */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $routine = Routine::findOrFail($id);
        $exercises = Exercise::all();
        $selectedExercises = $routine->trainingDays->flatMap(function ($day) {
            return $day->exercises->map(function ($pivotExercise) use ($day) {
                return [
                    'id' => $pivotExercise->id,
                    'exercise_id' => $pivotExercise->id_ejercicio,
                    'day_name' => $day->day_name,
                    'sets' => $pivotExercise->sets,
                    'reps' => $pivotExercise->reps,
                    'duration' => $pivotExercise->duration,
                    'duration_unit' => $pivotExercise->duration_unit,
                    'descansos' => $pivotExercise->rests,
                    'descansos_unidad' => $pivotExercise->rests_unit,
                ];
            });
        })->toArray();
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
        $this->authorizeAdmin();
        $this->normalizeDayInputs($request);

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
            'durations' => 'nullable|array',
            'durations.*' => 'nullable|integer|min:1',
            'duration_units' => 'nullable|array',
            'duration_units.*' => 'nullable|in:segundos,minutos',
            'descansos' => 'required|array',
            'descansos.*' => 'nullable|integer|min:0',
            'descansos_unidad' => 'required|array',
            'descansos_unidad.*' => 'required|in:segundos,minutos',
        ]);

        $exerciseFormats = Exercise::whereIn('id', $validated['exercises'])
            ->pluck('exercise_format', 'id')
            ->toArray();

        $errors = [];
        foreach ($validated['exercises'] as $exIndex => $exerciseId) {
            $format = $exerciseFormats[$exerciseId] ?? 'series_reps';

            if (($validated['exercise_days'][$exIndex] ?? null) === null) {
                continue;
            }

            if ($format === 'duration') {
                if (empty($validated['durations'][$exIndex])) {
                    $errors["durations.{$exIndex}"] = 'La duración es requerida para ejercicios por duración.';
                }
                if (empty($validated['duration_units'][$exIndex])) {
                    $errors["duration_units.{$exIndex}"] = 'La unidad de tiempo es requerida para ejercicios por duración.';
                }
            } else {
                if (empty($validated['sets'][$exIndex])) {
                    $errors["sets.{$exIndex}"] = 'Las series son requeridas para ejercicios de series y repeticiones.';
                }
                if (empty($validated['reps'][$exIndex])) {
                    $errors["reps.{$exIndex}"] = 'Las repeticiones son requeridas para ejercicios de series y repeticiones.';
                }
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

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

        // Remover días de entrenamiento anteriores (eliminará también sus ejercicios asignados)
        $routine->trainingDays()->delete();

        // Guardar nuevos días de entrenamiento
        foreach ($validated['training_days'] as $index => $day) {
            $routineDay = $routine->trainingDays()->create([
                'day_name' => $day,
                'day_order' => $index + 1,
            ]);

            // Asociar ejercicios a este día específico
            foreach ($validated['exercises'] as $exIndex => $exerciseId) {
                if (($validated['exercise_days'][$exIndex] ?? null) === $day) {
                    RoutineDayExercise::create([
                        'id_rutina_dias' => $routineDay->id,
                        'id_ejercicio' => $exerciseId,
                        'sets' => $validated['sets'][$exIndex] ?? null,
                        'reps' => $validated['reps'][$exIndex] ?? null,
                        'duration' => $validated['durations'][$exIndex] ?? null,
                        'duration_unit' => $validated['duration_units'][$exIndex] ?? null,
                        'rests' => $validated['descansos'][$exIndex] ?? null,
                        'rests_unit' => $validated['descansos_unidad'][$exIndex] ?? 'segundos',
                    ]);
                }
            }
        }

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$validated['name']}' actualizada exitosamente.");
    }

    /**
     * Eliminar rutina
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $routine = Routine::findOrFail($id);
        $routineName = $routine->name;

        // Los ejercicios seleccionados se eliminarán automáticamente por cascade
        $routine->delete();

        return redirect()->route('routines.index')
            ->with('success', "Rutina '{$routineName}' eliminada exitosamente.");
    }
}
