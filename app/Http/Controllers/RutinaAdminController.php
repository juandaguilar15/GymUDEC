<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\RutinaAdmin;
use App\Models\User;
use Illuminate\Http\Request;

class RutinaAdminController extends Controller
{
    /**
     * Verifica que el usuario sea administrador.
     */
    private function authorizeAdmin()
    {
        if (! auth()->check() || auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso denegado.');
        }
    }

    /**
     * Mostrar lista de rutinas asignadas por el administrador
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();
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
        $this->authorizeAdmin();
        $routines = Routine::where('status', 'publica')
            ->whereHas('users', function ($query) {
                $query->where('role', 'administrador');
            })
            ->get();

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
        $this->authorizeAdmin();
        $validated = $request->validate([
            'routine_id' => 'required|exists:routines,id',
            'student_email' => 'required|email|exists:users,email',
        ]);

        // Obtener datos de la rutina
        $routine = Routine::findOrFail($validated['routine_id']);
        if (! $routine->users()->where('role', 'administrador')->exists()) {
            return back()->withErrors(['routine_id' => 'Solo se pueden asignar rutinas creadas por administradores.']);
        }
        
        // Obtener datos del estudiante
        $student = User::where('email', $validated['student_email'])->firstOrFail();

        // Crear asignación
        RutinaAdmin::create([
            'routine_id' => $routine->id,
            'routine_name' => $routine->name,
            'student_name' => $student->name,
            'student_email' => $student->email,
        ]);

        $routine->users()->syncWithoutDetaching([$student->id]);

        return redirect()->route('rutinas.index')
            ->with('success', "Rutina '{$routine->name}' asignada a {$student->name} exitosamente.");
    }

    /**
     * Mostrar formulario para editar rutina asignada
     */
    public function edit($id)
    {
        $this->authorizeAdmin();
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
        $this->authorizeAdmin();
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
        if (! $routine->users()->where('role', 'administrador')->exists()) {
            return back()->withErrors(['routine_id' => 'Solo se pueden asignar rutinas creadas por administradores.']);
        }

        $rutinaAdmin->update([
            'routine_id' => $routine->id,
            'routine_name' => $routine->name,
            'student_name' => $student->name,
            'student_email' => $student->email,
        ]);

        $routine->users()->syncWithoutDetaching([$student->id]);

        return redirect()->route('rutinas.index')
            ->with('success', "Asignación de rutina actualizada exitosamente.");
    }

    /**
     * Eliminar rutina asignada
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();
        $rutinaAdmin = RutinaAdmin::findOrFail($id);
        $rutinaName = $rutinaAdmin->routine_name;
        $studentName = $rutinaAdmin->student_name;

        $rutinaAdmin->delete();

        return redirect()->route('rutinas.index')
            ->with('success', "Asignación de rutina '{$rutinaName}' a {$studentName} eliminada exitosamente.");
    }
}
