<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Routine;
use App\Models\RoutineDayExercise;
use App\Models\RoutineTrainingDay;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentRoutineController extends Controller
{
    /**
     * Verifica que el usuario actual sea estudiante.
     */
    private function authorizeStudent()
    {
        if (! auth()->check() || auth()->user()->role !== 'estudiante') {
            abort(403, 'Acceso denegado.');
        }
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
     * Muestra la lista de rutinas del estudiante.
     */
    public function index()
    {
        $this->authorizeStudent();
        $physicalInfo = $this->getStudentPhysicalInfo();
        $studentId = auth()->id();

        // Obtenemos todas las rutinas asociadas al estudiante
        $allRoutines = Routine::whereHas('users', function ($query) use ($studentId) {
            $query->where('users.id', $studentId); // Solución al error de ambigüedad
        })
        ->with(['trainingDays.exercises.exercise', 'users'])
        ->get();

        // Separar rutinas: asignadas por el administrador vs creadas por el estudiante
        // Las asignadas por admin tienen al menos un usuario con rol 'administrador'
        $assignedRoutines = $allRoutines->filter(function ($routine) {
            return $routine->users->contains('role', 'administrador');
        });

        $myRoutines = $allRoutines->filter(function ($routine) {
            return !$routine->users->contains('role', 'administrador');
        });

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

        return view('student.routines.create', [
            'exercises' => $exercises,
            'days_of_week' => $days_of_week,
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
    public function show($id)
    {
        $this->authorizeStudent();
        $this->getStudentPhysicalInfo();

        $routine = Routine::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })
        ->with('trainingDays.exercises.exercise')
        ->findOrFail($id);

        return view('student.routines.show', [
            'routine' => $routine,
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
        })->findOrFail($id);

        // Impedir edición de rutinas asignadas por administradores
        if ($routine->users()->where('role', 'administrador')->exists()) {
            return redirect()->route('student.routines.index')->with('error', 'No puedes editar rutinas asignadas.');
        }

        $exercises = Exercise::all();
        $days_of_week = ['lunes' => 'Lunes', 'martes' => 'Martes', 'miércoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sábado' => 'Sábado', 'domingo' => 'Domingo'];

        return view('student.routines.edit', [
            'routine' => $routine,
            'exercises' => $exercises,
            'days_of_week' => $days_of_week,
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

        $routine->update($validated);
        $routine->trainingDays()->delete();

        foreach ($validated['training_days'] as $index => $day) {
            $routineDay = $routine->trainingDays()->create(['day_name' => $day, 'day_order' => $index + 1]);
            
            // Buscamos los ejercicios correspondientes a este día
            foreach ($validated['exercises'] as $exIndex => $exerciseId) {
                if (($validated['exercise_days'][$exIndex] ?? null) === $day) {
                    RoutineDayExercise::create([
                        'id_rutina_dias' => $routineDay->id,
                        'id_ejercicio' => $exerciseId,
                        'sets' => $validated['sets'][$exIndex] ?? null,
                        'reps' => $validated['reps'][$exIndex] ?? null,
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
