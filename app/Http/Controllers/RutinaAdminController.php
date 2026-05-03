<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\RutinaAdmin;
use App\Models\User;
use Illuminate\Http\Request;

class RutinaAdminController extends Controller
{
    /**
     * Mostrar lista de rutinas asignadas por el administrador
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = RutinaAdmin::query();

        if ($search) {
            $query->where('routine_name', 'like', "%$search%")
                  ->orWhere('student_name', 'like', "%$search%")
                  ->orWhere('student_email', 'like', "%$search%");
        }

        $rutinas = $query->paginate(15);

        return view('admin.gym.rutinas.index', [
            'rutinas' => $rutinas,
            'search' => $search,
        ]);
    }

    /**
     * Mostrar formulario para crear/asignar nueva rutina
     */
    public function create()
    {
        $routines = Routine::where('status', 'publica')->get();
        $students = User::where('role', 'estudiante')->get();

        return view('admin.gym.rutinas.create', [
            'routines' => $routines,
            'students' => $students,
        ]);
    }

    /**
     * Guardar nueva rutina asignada
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'routine_id' => 'required|exists:routines,id',
            'student_email' => 'required|email|exists:users,email',
        ]);

        // Obtener datos de la rutina
        $routine = Routine::findOrFail($validated['routine_id']);
        
        // Obtener datos del estudiante
        $student = User::where('email', $validated['student_email'])->firstOrFail();

        // Crear asignación
        RutinaAdmin::create([
            'routine_id' => $routine->id,
            'routine_name' => $routine->name,
            'student_name' => $student->name,
            'student_email' => $student->email,
        ]);

        return redirect()->route('rutinas.index')
            ->with('success', "Rutina '{$routine->name}' asignada a {$student->name} exitosamente.");
    }

    /**
     * Mostrar formulario para editar rutina asignada
     */
    public function edit($id)
    {
        $rutinaAdmin = RutinaAdmin::findOrFail($id);
        $routines = Routine::where('status', 'publica')->get();
        $students = User::where('role', 'estudiante')->get();

        return view('admin.gym.rutinas.edit', [
            'rutinaAdmin' => $rutinaAdmin,
            'routines' => $routines,
            'students' => $students,
        ]);
    }

    /**
     * Actualizar rutina asignada
     */
    public function update(Request $request, $id)
    {
        $rutinaAdmin = RutinaAdmin::findOrFail($id);

        $validated = $request->validate([
            'routine_id' => 'required|exists:routines,id',
            'student_email' => 'required|email|exists:users,email',
        ]);

        // Obtener datos de la rutina
        $routine = Routine::findOrFail($validated['routine_id']);
        
        // Obtener datos del estudiante
        $student = User::where('email', $validated['student_email'])->firstOrFail();

        // Actualizar asignación
        $rutinaAdmin->update([
            'routine_id' => $routine->id,
            'routine_name' => $routine->name,
            'student_name' => $student->name,
            'student_email' => $student->email,
        ]);

        return redirect()->route('rutinas.index')
            ->with('success', "Asignación de rutina actualizada exitosamente.");
    }

    /**
     * Eliminar rutina asignada
     */
    public function destroy($id)
    {
        $rutinaAdmin = RutinaAdmin::findOrFail($id);
        $rutinaName = $rutinaAdmin->routine_name;
        $studentName = $rutinaAdmin->student_name;

        $rutinaAdmin->delete();

        return redirect()->route('rutinas.index')
            ->with('success', "Asignación de rutina '{$rutinaName}' a {$studentName} eliminada exitosamente.");
    }
}
