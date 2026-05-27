<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Machine;
use App\Models\Routine;
use App\Models\RoutineDayExercise;
use App\Models\RoutineTrainingDay;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;

class StudentRoutineController extends Controller implements HasMiddleware
{
    /**
     * Configurar middleware para el controlador
     */
    public static function middleware(): array
    {
        return ['auth', 'role:estudiante'];
    }

    /**
     * Obtiene la información física del estudiante.
     */
    private function getStudentPhysicalInfo()
    {
        $physicalInfo = auth()->user()->physicalInfo;

        if (! $physicalInfo) {
            abort(403, 'Necesitas registrar tu información física en enfermería antes de acceder a las rutinas.');
        }

        return $physicalInfo;
    }

    /**
     * Verifica que el usuario autenticado sea un estudiante.
     */
    private function authorizeStudent()
    {
        if (! auth()->check() || auth()->user()->role !== 'estudiante') {
            abort(403, 'Acceso restringido a estudiantes.');
        }
    }

    /**
     * Muestra la lista de rutinas del estudiante.
     */
    public function index()
    {
        if (! auth()->user()->physicalInfo) {
            return redirect()->route('dashboard')
                ->with('error', 'Necesitas registrar tu información física en enfermería antes de acceder a las rutinas.');
        }

        $physicalInfo = auth()->user()->physicalInfo;
        $studentId = auth()->id();

        // Obtenemos todas las rutinas asociadas al estudiante, ordenadas por fecha de creación.
        $allRoutines = Routine::whereHas('users', function ($query) use ($studentId) {
            $query->where('users.id', $studentId);
        })
        ->with(['trainingDays.exercises.exercise', 'users'])
        ->orderByDesc('created_at')
        ->get();

        // Separar rutinas: asignadas por administrador vs creadas por el estudiante.
        $assignedRoutines = $allRoutines->filter(function ($routine) {
            return $routine->users->contains('role', 'administrador');
        })->values();

        $myRoutines = $allRoutines->reject(function ($routine) {
            return $routine->users->contains('role', 'administrador');
        })->values();

        return view('student.routines.index', [
            'routines' => $allRoutines,
            'assignedRoutines' => $assignedRoutines,
            'myRoutines' => $myRoutines,
            'canCreate' => $physicalInfo->permisos === 'libre',
            'permisos' => $physicalInfo->permisos,
        ]);
    }

    /**
     * Muestra el formulario para que el estudiante cree una rutina.
     */
    public function create()
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')
                ->with('error', 'Tu permiso actual es limitado. No puedes crear nuevas rutinas.');
        }

        $exercises = Exercise::with('machine')->get();
        $machines = Machine::all();
        $days_of_week = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sábado' => 'Sábado',
            'domingo' => 'Domingo',
        ];

        // Preparar ejercicios para JavaScript
        $studentExercisesForJson = $exercises->map(function ($exercise) {
            return [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'muscle_group' => $exercise->muscle_group,
                'machine_id' => $exercise->machine_id,
                'exercise_format' => $exercise->exercise_format,
                'machine_name' => $exercise->machine ? $exercise->machine->name : '',
            ];
        })->toArray();

        return view('student.routines.create', [
            'exercises' => $exercises,
            'machines' => $machines,
            'days_of_week' => $days_of_week,
            'studentExercisesForJson' => $studentExercisesForJson,
            'existingSelectedDays' => [],
            'existingSelectedExercises' => [],
        ]);
    }

    /**
     * Guarda la nueva rutina creada por el estudiante.
     */
    public function store(Request $request)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')
                ->with('error', 'Tu permiso actual es limitado. No puedes crear nuevas rutinas.');
        }

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
            'sets' => 'nullable|array',
            'sets.*' => 'nullable|integer|min:1',
            'reps' => 'nullable|array',
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

        $routine = Routine::create([
            'name' => $validated['name'],
            'objective' => $validated['objective'],
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'days_per_week' => $validated['days_per_week'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        $routine->users()->sync([auth()->id()]);

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

        return redirect()->route('student.routines.index')
            ->with('success', "Rutina '{$validated['name']}' creada exitosamente.");
    }

    /**
     * Muestra los detalles de una rutina a la que el estudiante tiene acceso.
     */
    public function show(Request $request, $id)
    {
        $this->authorizeStudent();
        $this->getStudentPhysicalInfo();

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })
        ->with(['trainingDays.exercises.exercise', 'users'])
        ->findOrFail($id);

        return view('student.routines.show', [
            'routine' => $routine,
            'selectedDayName' => $request->query('day'),
        ]);
    }

    /**
     * Muestra el listado de rutinas públicas para estudiantes con permiso libre.
     */
    public function publicIndex(Request $request)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')
                ->with('error', 'Debes tener permiso libre para explorar rutinas públicas.');
        }

        $query = Routine::where('status', 'publica')->with(['users', 'trainingDays']);

        if ($request->filled('q')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('objective')) {
            $query->where('objective', $request->objective);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('days_per_week')) {
            $query->where('days_per_week', intval($request->days_per_week));
        }

        if ($request->filled('creator')) {
            if ($request->creator === 'administrador') {
                $query->whereHas('users', function ($sub) {
                    $sub->where('role', 'administrador');
                });
            } elseif ($request->creator === 'estudiante') {
                $query->whereDoesntHave('users', function ($sub) {
                    $sub->where('role', 'administrador');
                });
            }
        }

        $routines = $query->orderBy('name')->paginate(12)->withQueryString();
        $myRoutineIds = auth()->user()->routines()->pluck('routines.id')->toArray();

        return view('student.routines.public', [
            'routines' => $routines,
            'filters' => $request->only(['q', 'objective', 'level', 'days_per_week', 'creator']),
            'myRoutineIds' => $myRoutineIds,
        ]);
    }

    public function publicShow($id)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')
                ->with('error', 'Debes tener permiso libre para ver rutinas públicas.');
        }

        $routine = Routine::where('status', 'publica')
            ->with(['users', 'trainingDays.exercises.exercise'])
            ->findOrFail($id);

        $inMyRoutines = $routine->users->contains('id', auth()->id());

        return view('student.routines.public-show', [
            'routine' => $routine,
            'inMyRoutines' => $inMyRoutines,
        ]);
    }

    public function publicAdd($id)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.public.index')
                ->with('error', 'Debes tener permiso libre para agregar rutinas públicas.');
        }

        $routine = Routine::where('status', 'publica')->findOrFail($id);
        $routine->users()->syncWithoutDetaching([auth()->id()]);

        return redirect()->route('student.routines.public.show', $routine->id)
            ->with('success', 'La rutina se agregó a tus rutinas correctamente.');
    }

    /**
     * Muestra la pantalla de ejecución paso a paso para un día de rutina.
     */
    public function execute(Request $request, $id)
    {
        $this->authorizeStudent();
        $this->getStudentPhysicalInfo();

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })
        ->with(['trainingDays.exercises.exercise'])
        ->findOrFail($id);

        $dayName = $request->query('day');
        $trainingDays = $routine->trainingDays->sortBy('day_order')->values();
        $selectedDay = null;
        $selectedDayExercises = [];

        if ($dayName) {
            $selectedDay = $trainingDays->first(function ($day) use ($dayName) {
                return strtolower($day->day_name) === strtolower($dayName);
            });
        }

        if ($selectedDay) {
            $selectedDayExercises = $selectedDay->exercises->map(function ($exerciseItem) {
                return [
                    'exercise_name' => $exerciseItem->exercise?->name ?? 'Ejercicio no disponible',
                    'format' => $exerciseItem->exercise?->exercise_format ?? 'series_reps',
                    'duration' => $exerciseItem->duration,
                    'duration_unit' => $exerciseItem->duration_unit,
                    'sets' => $exerciseItem->sets,
                    'reps' => $exerciseItem->reps,
                    'rests' => $exerciseItem->rests,
                    'rests_unit' => $exerciseItem->rests_unit,
                ];
            })->toArray();
        }

        return view('student.routines.execute', [
            'routine' => $routine,
            'trainingDays' => $trainingDays,
            'selectedDay' => $selectedDay,
            'selectedDayExercises' => $selectedDayExercises,
        ]);
    }

    /**
     * Muestra el formulario para editar una rutina creada por el estudiante.
     */
    public function edit($id)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')->with('error', 'Tu permiso es limitado.');
        }

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })->with(['trainingDays.exercises.exercise'])->findOrFail($id);

        // Impedir edición de rutinas asignadas por administradores
        if ($routine->users()->where('role', 'administrador')->exists()) {
            return redirect()->route('student.routines.index')->with('error', 'No puedes editar rutinas asignadas.');
        }

        $exercises = Exercise::with('machine')->get();
        $machines = Machine::all();
        $days_of_week = ['lunes' => 'Lunes', 'martes' => 'Martes', 'miércoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sábado' => 'Sábado', 'domingo' => 'Domingo'];

        // Preparar ejercicios para JavaScript
        $studentExercisesForJson = $exercises->map(function ($exercise) {
            return [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'muscle_group' => $exercise->muscle_group,
                'machine_id' => $exercise->machine_id,
                'exercise_format' => $exercise->exercise_format,
                'machine_name' => $exercise->machine ? $exercise->machine->name : '',
            ];
        })->toArray();

        // Obtener días seleccionados existentes
        $existingSelectedDays = $routine->trainingDays->pluck('day_name')->toArray();

        // Obtener ejercicios seleccionados existentes organizados por día
        $existingSelectedExercises = [];
        foreach ($routine->trainingDays as $day) {
            $dayName = $day->day_name;
            if (!isset($existingSelectedExercises[$dayName])) {
                $existingSelectedExercises[$dayName] = [];
            }

            foreach ($day->exercises as $pivotExercise) {
                $exercise = $pivotExercise->exercise;
                $existingSelectedExercises[$dayName][] = [
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'muscle_group' => $exercise->muscle_group,
                    'machine_id' => $exercise->machine_id,
                    'exercise_format' => $exercise->exercise_format,
                    'machine_name' => $exercise->machine ? $exercise->machine->name : '',
                    'sets' => $pivotExercise->sets,
                    'reps' => $pivotExercise->reps,
                    'duration' => $pivotExercise->duration,
                    'duration_unit' => $pivotExercise->duration_unit ?? 'segundos',
                    'rests' => $pivotExercise->weight ?? '', // Usando weight como rests si es necesario
                    'rests_unit' => 'segundos',
                ];
            }
        }

        return view('student.routines.create', [
            'routine' => $routine,
            'exercises' => $exercises,
            'machines' => $machines,
            'days_of_week' => $days_of_week,
            'studentExercisesForJson' => $studentExercisesForJson,
            'existingSelectedDays' => $existingSelectedDays,
            'existingSelectedExercises' => $existingSelectedExercises,
        ]);
    }

    /**
     * Actualiza la rutina creada por el estudiante.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')->with('error', 'No tienes permisos.');
        }

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })->findOrFail($id);

        if ($routine->users()->where('role', 'administrador')->exists()) {
            return redirect()->route('student.routines.index')->with('error', 'Las rutinas asignadas no pueden ser modificadas.');
        }

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
            'sets' => 'nullable|array',
            'sets.*' => 'nullable|integer|min:1',
            'reps' => 'nullable|array',
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

        $routine->update($validated);
        $routine->trainingDays()->delete();

        foreach ($validated['training_days'] as $index => $day) {
            $routineDay = $routine->trainingDays()->create(['day_name' => $day, 'day_order' => $index + 1]);
            
            // Buscamos los ejercicios correspondientes a este día
            foreach ($validated['exercises'] as $exIndex => $exerciseId) {
                if (($validated['exercise_days'][$exIndex] ?? null) === $day) {
                    $format = Exercise::find($exerciseId)?->exercise_format ?? 'series_reps';
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

        return redirect()->route('student.routines.index')->with('success', 'Rutina actualizada correctamente.');
    }

    /**
     * Elimina una rutina creada por el estudiante.
     */
    public function destroy($id)
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        if ($physicalInfo->permisos !== 'libre') {
            return redirect()->route('student.routines.index')->with('error', 'No tienes permisos.');
        }

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })->findOrFail($id);

        if ($routine->users()->where('role', 'administrador')->exists()) {
            return redirect()->route('student.routines.index')->with('error', 'No puedes eliminar una rutina asignada por un administrador.');
        }

        $routine->delete();
        return redirect()->route('student.routines.index')->with('success', 'Rutina eliminada exitosamente.');
    }

    /**
     * Muestra la información física del estudiante.
     */
    public function myPhysicalInfo()
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();

        return view('my-physical-info', [
            'physicalInfo' => $physicalInfo,
        ]);
    }
}
