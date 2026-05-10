<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhysicalInfo;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    private function authorizeNurse()
    {
        if (! auth()->check() || auth()->user()->role !== 'enfermero') {
            abort(403, 'Acceso denegado.');
        }
    }

    // Mostrar formulario para buscar estudiante
    public function searchStudentForm()
    {
        $this->authorizeNurse();
        return view('nurse.search-student');
    }
    
    // Buscar estudiante por email
    public function searchStudent(Request $request)
    {
        $this->authorizeNurse();
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
        $this->authorizeNurse();
        $user = User::where('email', $email)->firstOrFail();
        $physicalInfo = PhysicalInfo::where('email', $email)->first();
        
        return view('nurse.physical-form', [
            'user' => $user,
            'physicalInfo' => $physicalInfo,
        ]);
    }
    
    // Guardar/actualizar información física
    public function savePhysicalInfo(Request $request, $email)
    {
        $this->authorizeNurse();
        $user = User::where('email', $email)->firstOrFail();
        
        $validated = $request->validate([
            'age' => 'required|integer|min:15|max:100',
            'date_of_birth' => 'required|date|before:today',
            'height' => 'required|numeric|min:1|max:3',
            'gender' => 'required|in:masculino,femenino,otro',
            'weight' => 'required|numeric|min:20|max:300',
            'condition' => 'nullable|string|max:1000',
            'recommendation' => 'nullable|string|max:1000',
            'permisos' => 'required|in:libre,limitado',
        ]);
        
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
        $this->authorizeNurse();
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
        $this->authorizeNurse();
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
        $this->authorizeNurse();
        $user = User::where('email', $email)->firstOrFail();
        $physicalInfo = PhysicalInfo::where('email', $email)->firstOrFail();
        
        $physicalInfo->delete();
        
        return redirect()->route('nurse.list-students')
            ->with('success', "Información física de {$user->name} eliminada correctamente.");
    }
}
