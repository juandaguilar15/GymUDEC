<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\AnalyticsController;

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
    
    // Rutas de análisis y estadísticas
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export-csv', [AnalyticsController::class, 'exportCsv'])->name('analytics.export-csv');
});
