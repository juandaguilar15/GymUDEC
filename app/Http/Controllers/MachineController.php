<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Mostrar listado de máquinas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Machine::query();
        
        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%");
        }
        
        $machines = $query->paginate(15);
        
        return view('admin.gym.machines.index', [
            'machines' => $machines,
            'search' => $search,
        ]);
    }

    /**
     * Mostrar formulario para crear máquina
     */
    public function create()
    {
        return view('admin.gym.machines.create');
    }

    /**
     * Guardar máquina en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|url',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.url' => 'La URL de la imagen debe ser válida',
        ]);

        Machine::create($validated);

        return redirect()->route('machines.index')->with('success', 'Máquina creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar máquina
     */
    public function edit(Machine $machine)
    {
        return view('admin.gym.machines.edit', [
            'machine' => $machine,
        ]);
    }

    /**
     * Actualizar máquina en la base de datos
     */
    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|url',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.url' => 'La URL de la imagen debe ser válida',
        ]);

        $machine->update($validated);

        return redirect()->route('machines.index')->with('success', 'Máquina actualizada exitosamente.');
    }

    /**
     * Eliminar máquina
     */
    public function destroy(Machine $machine)
    {
        $machineName = $machine->name;
        $machine->delete();

        return back()->with('success', "Máquina '{$machineName}' eliminada exitosamente.");
    }
}

