<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;

class ExerciseController extends Controller implements HasMiddleware
{
    /**
     * Middleware para asegurar que solo el admin acceda
     */
    public static function middleware(): array
    {
        return ['auth', 'role:administrador'];
    }

    /**
     * Mostrar listado de ejercicios
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $machineId = $request->input('machine_id');

        $query = Exercise::with('machine');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%")
                  ->orWhere('muscle_group', 'like', "%$search%");
            });
        }

        if ($type && in_array($type, ['cardio', 'fuerza'])) {
            $query->where('type', $type);
        }

        if ($machineId) {
            $query->where('machine_id', $machineId);
        }

        $exercises = $query->paginate(15)->withQueryString();
        $machines = Machine::where('status', true)->get();

        return view('admin.gym.exercises.index', [
            'exercises' => $exercises,
            'search' => $search,
            'type' => $type,
            'machineId' => $machineId,
            'machines' => $machines,
        ]);
    }

    /**
     * Mostrar formulario para crear ejercicio
     */
    public function create()
    {
        $machines = Machine::where('status', true)->get();
        
        return view('admin.gym.exercises.create', [
            'machines' => $machines,
        ]);
    }

    /**
     * Guardar ejercicio en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:exercises,name',
            'type' => 'required|in:cardio,fuerza',
            'description' => 'required|string',
            'muscle_group' => 'required|string|max:255',
            'machine_id' => 'required|exists:machines,id',
            'exercise_format' => 'required|in:series_reps,duration',
            'image_url' => 'nullable|image|max:2048',
            'media_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
        ], [
            'name.required' => 'El nombre del ejercicio es requerido',
            'name.unique' => 'Ya existe un ejercicio con ese nombre. Por favor utiliza un nombre distinto.',
            'type.required' => 'El tipo de ejercicio es requerido',
            'type.in' => 'El tipo debe ser: cardio o fuerza',
            'description.required' => 'La descripción del ejercicio es requerida',
            'muscle_group.required' => 'El grupo muscular es requerido',
            'machine_id.required' => 'Debe seleccionar una máquina',
            'exercise_format.required' => 'El formato del ejercicio es requerido',
            'machine_id.exists' => 'La máquina seleccionada no existe',
            'image_url.image' => 'El archivo debe ser una imagen válida (jpg, png, etc.)',
            'image_url.max' => 'La imagen no puede superar los 2MB.',
            'media_url.file' => 'El archivo debe ser un video válido',
            'media_url.mimes' => 'El video debe ser MP4, MOV, AVI o WMV.',
            'media_url.max' => 'El video no puede superar los 20MB.',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('exercises/images', 'public');
        }
        if ($request->hasFile('media_url')) {
            $validated['media_url'] = $request->file('media_url')->store('exercises/videos', 'public');
        }

        Exercise::create($validated);

        return redirect()->route('exercises.index')->with('success', 'Ejercicio creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar ejercicio
     */
    public function edit(Exercise $exercise)
    {
        $machines = Machine::where('status', true)->get();
        
        return view('admin.gym.exercises.edit', [
            'exercise' => $exercise,
            'machines' => $machines,
        ]);
    }

    /**
     * Actualizar ejercicio en la base de datos
     */
    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:exercises,name,' . $exercise->id,
            'type' => 'required|in:cardio,fuerza',
            'description' => 'required|string',
            'muscle_group' => 'required|string|max:255',
            'machine_id' => 'required|exists:machines,id',
            'exercise_format' => 'required|in:series_reps,duration',
            'image_url' => 'nullable|image|max:2048',
            'media_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
        ], [
            'name.required' => 'El nombre del ejercicio es requerido',
            'name.unique' => 'Ya existe un ejercicio con ese nombre. Por favor utiliza un nombre distinto.',
            'type.required' => 'El tipo de ejercicio es requerido',
            'type.in' => 'El tipo debe ser: cardio o fuerza',
            'description.required' => 'La descripción del ejercicio es requerida',
            'muscle_group.required' => 'El grupo muscular es requerido',
            'machine_id.required' => 'Debe seleccionar una máquina',
            'exercise_format.required' => 'El formato del ejercicio es requerido',
            'machine_id.exists' => 'La máquina seleccionada no existe',
            'image_url.image' => 'El archivo debe ser una imagen válida',
        ]);

        // Evitar que los campos de archivo se sobrescriban con null si no se sube uno nuevo
        if (!$request->hasFile('image_url')) {
            unset($validated['image_url']);
        } else {
            // Borrar imagen anterior si existe y no es una URL externa
            if ($exercise->getRawOriginal('image_url') && !filter_var($exercise->getRawOriginal('image_url'), FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($exercise->getRawOriginal('image_url'));
            }
            $validated['image_url'] = $request->file('image_url')->store('exercises/images', 'public');
        }

        if (!$request->hasFile('media_url')) {
            unset($validated['media_url']);
        } else {
            // Borrar video anterior si existe y no es una URL externa
            if ($exercise->getRawOriginal('media_url') && !filter_var($exercise->getRawOriginal('media_url'), FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($exercise->getRawOriginal('media_url'));
            }
            $validated['media_url'] = $request->file('media_url')->store('exercises/videos', 'public');
        }

        $exercise->update($validated);

        return redirect()->route('exercises.index')->with('success', 'Ejercicio actualizado exitosamente.');
    }

    /**
     * Mostrar ejercicio individual
     */
    public function show(Exercise $exercise)
    {
        return view('admin.gym.exercises.show', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * Eliminar ejercicio
     */
    public function destroy(Exercise $exercise)
    {
        $exerciseName = $exercise->name;

        if ($exercise->routineDayExercises()->exists()) {
            return back()->withErrors(['delete' => 'No se puede eliminar este ejercicio porque ya está asignado a una rutina. Desasigna o elimina primero la rutina.']);
        }

        // Eliminar archivos físicos del storage si existen
        if ($exercise->getRawOriginal('image_url') && !filter_var($exercise->getRawOriginal('image_url'), FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($exercise->getRawOriginal('image_url'));
        }
        if ($exercise->getRawOriginal('media_url') && !filter_var($exercise->getRawOriginal('media_url'), FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($exercise->getRawOriginal('media_url'));
        }

        $exercise->delete();

        return back()->with('success', "Ejercicio '{$exerciseName}' eliminado exitosamente.");
    }
}
