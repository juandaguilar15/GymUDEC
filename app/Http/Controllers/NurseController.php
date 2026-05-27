<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhysicalInfo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class NurseController extends Controller implements HasMiddleware
{
    /**
     * Middleware para asegurar que solo el enfermero acceda
     */
    public static function middleware(): array
    {
        return ['auth', 'role:enfermero'];
    }

    // Mostrar formulario para buscar estudiante
    public function searchStudentForm()
    {
        return view('nurse.search-student');
    }
    
    // Buscar estudiante por email
    public function searchStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|email|ends_with:@ucundinamarca.edu.co',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Estudiante no encontrado con ese correo.']);
        }
        
        if ($user->role !== 'estudiante') {
            return back()->withErrors(['email' => 'Este correo no pertenece a un estudiante.']);
        }
        
        return redirect()->route('nurse.physical-form', ['email' => $user->email]);
    }
    
    // Mostrar formulario de información física
    public function showPhysicalForm($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $physicalInfo = PhysicalInfo::where('email', $email)->first();
        
        return view('nurse.physical-form', [
            'user' => $user,
            'physicalInfo' => $physicalInfo,
        ]);
    }
    
    // Guardar/actualizar información física
    public function savePhysicalInfo(\App\Http\Requests\StorePhysicalInfoRequest $request, $email)
    {
        $user = User::where('email', $email)->firstOrFail();

        // El FormRequest ya valida y autoriza
        $validated = $request->validated();

        $physicalInfo = PhysicalInfo::updateOrCreate(
            ['email' => $email],
            array_merge($validated, ['email' => $email])
        );

        return redirect()->route('nurse.search-student')
            ->with('success', "Información física de {$user->name} guardada exitosamente.");
    }
    
    // Ver información física de un estudiante
    public function viewStudentInfo($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $physicalInfo = PhysicalInfo::where('email', $email)->firstOrFail();
        
        return view('nurse.view-student-info', [
            'user' => $user,
            'physicalInfo' => $physicalInfo,
        ]);
    }
    
    // Listar todos los estudiantes con información física registrada
    public function listStudents()
    {
        $physicalInfos = PhysicalInfo::with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('nurse.list-students', [
            'physicalInfos' => $physicalInfos,
        ]);
    }
    
    // Eliminar información física de un estudiante
    public function deletePhysicalInfo($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $physicalInfo = PhysicalInfo::where('email', $email)->firstOrFail();
        
        $physicalInfo->delete();
        
        return redirect()->route('nurse.list-students')
            ->with('success', "Información física de {$user->name} eliminada correctamente.");
    }

    // Obtener información física en formato JSON (para AJAX)
    public function getPhysicalInfoJson($email)
    {
        if (! auth()->check() || !in_array(auth()->user()->role, ['administrador', 'enfermero'])) {
            abort(403, 'Acceso denegado.');
        }

        $physicalInfo = PhysicalInfo::where('email', $email)->firstOrFail();
        
        return response()->json($physicalInfo, 200);
    }
}
