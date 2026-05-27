<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\RutinaAdminController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\StudentRoutineController;
use App\Http\Controllers\NoticeController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas de Recuperación de Contraseña
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta de dashboard (temporal)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Rutas del enfermero (enfermería)
Route::middleware(['auth'])->group(function () {
    Route::get('/enfermero/buscar', [NurseController::class, 'searchStudentForm'])->name('nurse.search-student');
    Route::post('/enfermero/buscar', [NurseController::class, 'searchStudent'])->name('nurse.search');
    Route::get('/enfermero/estudiantes', [NurseController::class, 'listStudents'])->name('nurse.list-students');
    Route::get('/enfermero/info-fisica/{email}', [NurseController::class, 'showPhysicalForm'])->name('nurse.physical-form');
    Route::post('/enfermero/info-fisica/{email}', [NurseController::class, 'savePhysicalInfo'])->name('nurse.save-info');
    Route::get('/enfermero/ver/{email}', [NurseController::class, 'viewStudentInfo'])->name('nurse.view-info');
    Route::delete('/enfermero/eliminar/{email}', [NurseController::class, 'deletePhysicalInfo'])->name('nurse.delete-info');
    
    // API para obtener información física (usado en asignación de rutinas)
    Route::get('/api/student-physical-info/{email}', [NurseController::class, 'getPhysicalInfoJson'])->name('api.physical-info');
    
    // Rutas de análisis y estadísticas
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export-csv', [AnalyticsController::class, 'exportCsv'])->name('analytics.export-csv');
    
    // Rutas del administrador
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // Eliminamos el redirect redundante para evitar bucles de redirección
    Route::get('/admin/analytics', [AdminController::class, 'viewAnalytics'])->name('admin.analytics');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
    Route::post('/admin/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.update-user-role');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    
    // Rutas de Gestión de Base de Datos (Backup y Restauración)
    Route::get('/admin/database/export', [DatabaseController::class, 'export'])->name('admin.database.export');
    Route::post('/admin/database/import', [DatabaseController::class, 'import'])->name('admin.database.import');
    
    // Rutas de Gestión de Gimnasio - Máquinas
    Route::get('/admin/gym/machines', [MachineController::class, 'index'])->name('machines.index');
    Route::get('/admin/gym/machines/create', [MachineController::class, 'create'])->name('machines.create');
    Route::post('/admin/gym/machines', [MachineController::class, 'store'])->name('machines.store');
    Route::get('/admin/gym/machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::get('/admin/gym/machines/{machine}', [MachineController::class, 'show'])->name('machines.show');
    Route::put('/admin/gym/machines/{machine}', [MachineController::class, 'update'])->name('machines.update');
    Route::delete('/admin/gym/machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');
    
    // Rutas de Gestión de Gimnasio - Ejercicios
    Route::get('/admin/gym/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/admin/gym/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
    Route::post('/admin/gym/exercises', [ExerciseController::class, 'store'])->name('exercises.store');
    Route::get('/admin/gym/exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');
    Route::get('/admin/gym/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');
    Route::put('/admin/gym/exercises/{exercise}', [ExerciseController::class, 'update'])->name('exercises.update');
    Route::delete('/admin/gym/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');
    
    // Rutas de Gestión de Gimnasio - Rutinas (CRUD)
    Route::get('/admin/gym/routines', [RoutineController::class, 'index'])->name('routines.index');
    Route::get('/admin/gym/routines/create', [RoutineController::class, 'create'])->name('routines.create');
    Route::post('/admin/gym/routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::get('/admin/gym/routines/{routine}/edit', [RoutineController::class, 'edit'])->name('routines.edit');
    Route::put('/admin/gym/routines/{routine}', [RoutineController::class, 'update'])->name('routines.update');
    Route::delete('/admin/gym/routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');
    
    // Rutas de Gestión de Gimnasio - Rutinas Asignadas por Admin
    Route::get('/admin/gym/rutinas', [RutinaAdminController::class, 'index'])->name('rutinas.index');
    Route::get('/admin/gym/rutinas/create', [RutinaAdminController::class, 'create'])->name('rutinas.create');
    Route::post('/admin/gym/rutinas', [RutinaAdminController::class, 'store'])->name('rutinas.store');
    Route::get('/admin/gym/rutinas/{rutinaAdmin}/edit', [RutinaAdminController::class, 'edit'])->name('rutinas.edit');
    Route::put('/admin/gym/rutinas/{rutinaAdmin}', [RutinaAdminController::class, 'update'])->name('rutinas.update');
    Route::delete('/admin/gym/rutinas/{rutinaAdmin}', [RutinaAdminController::class, 'destroy'])->name('rutinas.destroy');

    // Rutas de estudiantes
    Route::get('/student/routines', [StudentRoutineController::class, 'index'])->name('student.routines.index');
    Route::get('/student/routines/public', [StudentRoutineController::class, 'publicIndex'])->name('student.routines.public.index');
    Route::get('/student/routines/public/{routine}', [StudentRoutineController::class, 'publicShow'])->name('student.routines.public.show');
    Route::post('/student/routines/public/{routine}/add', [StudentRoutineController::class, 'publicAdd'])->name('student.routines.public.add');
    Route::get('/student/routines/create', [StudentRoutineController::class, 'create'])->name('student.routines.create');
    Route::post('/student/routines', [StudentRoutineController::class, 'store'])->name('student.routines.store');
    Route::get('/student/routines/{routine}', [StudentRoutineController::class, 'show'])->name('student.routines.show');
    Route::get('/student/routines/{routine}/edit', [StudentRoutineController::class, 'edit'])->name('student.routines.edit');
    Route::put('/student/routines/{routine}', [StudentRoutineController::class, 'update'])->name('student.routines.update');
    Route::delete('/student/routines/{routine}', [StudentRoutineController::class, 'destroy'])->name('student.routines.destroy');
    Route::get('/student/routines/{routine}/execute', [StudentRoutineController::class, 'execute'])->name('student.routines.execute');
    Route::get('/student/my-physical-info', [StudentRoutineController::class, 'myPhysicalInfo'])->name('student.my-physical-info');

    // Rutas de Avisos para estudiantes (ver avisos y notificaciones guardadas)
    Route::get('/student/notices', [NoticeController::class, 'studentIndex'])->name('student.notices.index');
    Route::post('/student/notices/{notification}/mark-read', [NoticeController::class, 'markAsRead'])->name('student.notices.mark-read');
    Route::post('/student/notices/mark-all-read', [NoticeController::class, 'markAllAsRead'])->name('student.notices.mark-all-read');
    Route::get('/student/notifications/json', [NoticeController::class, 'notificationsJson'])->name('student.notices.json');
    // Rutas de Avisos (CRUD para Admin)
    Route::resource('admin/notices', NoticeController::class)->names('admin.notices');
});
