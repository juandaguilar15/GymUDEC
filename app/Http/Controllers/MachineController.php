<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // Asegurar que el status sea un booleano incluso si el checkbox no se envía
        $request->merge(['status' => $request->has('status') ? 1 : 0]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.image' => 'El archivo debe ser una imagen válida (jpg, png, etc.)',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('machines', 'public');
        }

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
        // Asegurar que el status sea un booleano incluso si el checkbox no se envía
        $request->merge(['status' => $request->has('status') ? 1 : 0]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.image' => 'El archivo debe ser una imagen válida',
        ]);

        // Evitar que la imagen se borre si no se sube una nueva
        if (!$request->hasFile('image_url')) {
            unset($validated['image_url']);
        } else {
            // Borrar imagen anterior física si existe
            if ($machine->getRawOriginal('image_url') && !filter_var($machine->getRawOriginal('image_url'), FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($machine->getRawOriginal('image_url'));
            }
            $validated['image_url'] = $request->file('image_url')->store('machines', 'public');
        }

        $machine->update($validated);

        return redirect()->route('machines.index')->with('success', 'Máquina actualizada exitosamente.');
    }

    /**
     * Eliminar máquina
     */
    public function destroy(Machine $machine)
    {
        $machineName = $machine->name;

        // Eliminar imagen física del storage antes de borrar el registro
        if ($machine->getRawOriginal('image_url') && !filter_var($machine->getRawOriginal('image_url'), FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($machine->getRawOriginal('image_url'));
        }

        $machine->delete();

        return back()->with('success', "Máquina '{$machineName}' eliminada exitosamente.");
    }
}
