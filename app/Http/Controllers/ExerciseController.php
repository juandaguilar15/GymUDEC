<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Machine;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Mostrar listado de ejercicios
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Exercise::with('machine');
        
        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%")
                  ->orWhere('muscle_group', 'like', "%$search%");
        }
        
        $exercises = $query->paginate(15);
        
        return view('admin.gym.exercises.index', [
            'exercises' => $exercises,
            'search' => $search,
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza',
            'description' => 'required|string',
            'muscle_group' => 'required|string|max:255',
            'machine_id' => 'required|exists:machines,id',
            'image_url' => 'nullable|url',
            'media_url' => 'nullable|url',
        ], [
            'name.required' => 'El nombre del ejercicio es requerido',
            'type.required' => 'El tipo de ejercicio es requerido',
            'type.in' => 'El tipo debe ser: cardio o fuerza',
            'description.required' => 'La descripción del ejercicio es requerida',
            'muscle_group.required' => 'El grupo muscular es requerido',
            'machine_id.required' => 'Debe seleccionar una máquina',
            'machine_id.exists' => 'La máquina seleccionada no existe',
            'image_url.url' => 'La URL de la imagen debe ser válida',
            'media_url.url' => 'La URL del media debe ser válida',
        ]);

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
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza',
            'description' => 'required|string',
            'muscle_group' => 'required|string|max:255',
            'machine_id' => 'required|exists:machines,id',
            'image_url' => 'nullable|url',
            'media_url' => 'nullable|url',
        ], [
            'name.required' => 'El nombre del ejercicio es requerido',
            'type.required' => 'El tipo de ejercicio es requerido',
            'type.in' => 'El tipo debe ser: cardio o fuerza',
            'description.required' => 'La descripción del ejercicio es requerida',
            'muscle_group.required' => 'El grupo muscular es requerido',
            'machine_id.required' => 'Debe seleccionar una máquina',
            'machine_id.exists' => 'La máquina seleccionada no existe',
            'image_url.url' => 'La URL de la imagen debe ser válida',
            'media_url.url' => 'La URL del media debe ser válida',
        ]);

        $exercise->update($validated);

        return redirect()->route('exercises.index')->with('success', 'Ejercicio actualizado exitosamente.');
    }

    /**
     * Eliminar ejercicio
     */
    public function destroy(Exercise $exercise)
    {
        $exerciseName = $exercise->name;
        $exercise->delete();

        return back()->with('success', "Ejercicio '{$exerciseName}' eliminado exitosamente.");
    }
}
