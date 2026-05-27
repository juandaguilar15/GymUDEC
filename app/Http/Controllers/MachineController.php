<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Notice;
use App\Models\Routine;
use App\Models\User;
use App\Notifications\GymNoticeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;

class MachineController extends Controller implements HasMiddleware
{
    /**
     * Aplicar middleware de autenticación y rol en el constructor
     */
    public static function middleware(): array
    {
        return ['auth', 'role:administrador'];
    }

    /**
     * Mostrar listado de máquinas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status'); // 'activa', 'inactiva', o null (todas)

        $query = Machine::query()
            ->withCount('exercises');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%");
            });
        }

        if ($type && in_array($type, ['cardio', 'fuerza', 'mixto'])) {
            $query->where('type', $type);
        }

        if ($status !== null && $status !== '') {
            $statusBool = $status === 'activa' ? true : false;
            $query->where('status', $statusBool);
        }

        // Conteos: total en el sistema y total tras aplicar filtros
        $totalMachines = Machine::count();
        $filteredCount = $query->count();

        $machines = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.gym.machines.index', [
            'machines' => $machines,
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'totalMachines' => $totalMachines,
            'filteredCount' => $filteredCount,
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
            'name' => 'required|string|max:255|unique:machines,name',
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'name.unique' => 'Ya existe una máquina con ese nombre. Usa otro nombre.',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.image' => 'El archivo debe ser una imagen válida (jpg, png, etc.)',
            'image_url.max' => 'La imagen no puede superar los 2MB.',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('machines', 'public');
        }

        Machine::create($validated);

        return redirect()->route('machines.index')->with('success', 'Máquina creada exitosamente. ✅');
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
     * Mostrar detalle de una máquina
     */
    public function show(Machine $machine)
    {
        return view('admin.gym.machines.show', [
            'machine' => $machine->load('exercises'),
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
            'name' => 'required|string|max:255|unique:machines,name,' . $machine->id,
            'type' => 'required|in:cardio,fuerza,mixto',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre de la máquina es requerido',
            'name.unique' => 'Ya existe una máquina con ese nombre. Usa otro nombre.',
            'type.required' => 'El tipo de máquina es requerido',
            'type.in' => 'El tipo debe ser: cardio, fuerza o mixto',
            'image_url.image' => 'El archivo debe ser una imagen válida',
            'image_url.max' => 'La imagen no puede superar los 2MB.',
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

        // Detectar cambio de estado: si antes estaba activa y ahora queda inactiva
        $wasActive = $machine->getOriginal('status');

        $machine->update($validated);

        $isNowActive = $machine->status;

        if ($wasActive && ! $isNowActive) {
            // Crear aviso automático y notificar a todos los usuarios
            $title = "Máquina '{$machine->name}' fuera de servicio";
            $content = "La máquina '{$machine->name}' se marcó como fuera de servicio. Por favor revisa el mantenimiento y actualiza su estado cuando esté disponible.";

            $notice = Notice::create([
                'title' => $title,
                'content' => $content,
                'type' => 'warning',
                'is_active' => true,
                'admin_id' => auth()->id(),
            ]);

            $users = User::all();
            Notification::send($users, new GymNoticeNotification($notice));
        }

        return redirect()->route('machines.index')->with('success', 'Máquina actualizada exitosamente.');
    }

    /**
     * Eliminar máquina
     */
    public function destroy(Machine $machine)
    {
        $machineName = $machine->name;

        $exercisesCount = $machine->exercises()->count();
        $routinesCount = Routine::whereHas('dayExercises.exercise', function ($query) use ($machine) {
            $query->where('machine_id', $machine->id);
        })->count();

        if ($exercisesCount > 0 || $routinesCount > 0) {
            $errorMsg = "No se puede eliminar <strong>'{$machineName}'</strong> porque tiene asociadas: ";
            $details = [];
            if ($exercisesCount > 0) {
                $details[] = "<strong>{$exercisesCount}</strong> " . ($exercisesCount === 1 ? 'ejercicio' : 'ejercicios');
            }
            if ($routinesCount > 0) {
                $details[] = "<strong>{$routinesCount}</strong> " . ($routinesCount === 1 ? 'rutina' : 'rutinas');
            }
            $errorMsg .= implode(' y ', $details) . ". Elimina o reasigna primero esas dependencias.";

            return back()->withErrors(['delete' => $errorMsg]);
        }

        // Eliminar imagen física del storage antes de borrar el registro
        if ($machine->getRawOriginal('image_url') && !filter_var($machine->getRawOriginal('image_url'), FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($machine->getRawOriginal('image_url'));
        }

        $machine->delete();

        return back()->with('success', "Máquina <strong>'{$machineName}'</strong> eliminada exitosamente.");
    }
}
