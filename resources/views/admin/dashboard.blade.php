﻿@extends('layouts.admin')

@section('title', 'Panel de Administración - GymUDEC')
@section('page-title', 'Panel de Administración')
@section('page-subtitle', 'Tu espacio administrativo con accesos directos y navegación rápida hacia los módulos más importantes.')
@section('page-actions')
    {{-- Encabezado sin botones para el dashboard administrativo --}}
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-[2fr_1fr] mb-8">
        <section class="admin-card">
            <p class="admin-section-label">Bienvenida administrativa</p>
            <h2 class="text-3xl font-bold text-emerald-950 mb-4">¡Hola, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-600 mb-6">Desde aquí puedes acceder rápidamente a los módulos más importantes. Haz primero las tareas clave y luego navega por el resto del sistema.</p>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.users') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">👥</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Usuarios</h3>
                        <p class="dashboard-action-card-text">Gestiona roles, permisos y búsquedas.</p>
                    </div>
                </a>
                <a href="{{ route('machines.index') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">🏋️</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Máquinas</h3>
                        <p class="dashboard-action-card-text">Controla el equipo del gimnasio.</p>
                    </div>
                </a>
                <a href="{{ route('exercises.index') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">🏃</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Ejercicios</h3>
                        <p class="dashboard-action-card-text">Gestiona los movimientos y detalles.</p>
                    </div>
                </a>
                <a href="{{ route('routines.index') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">📋</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Rutinas</h3>
                        <p class="dashboard-action-card-text">Crea y edita rutinas disponibles.</p>
                    </div>
                </a>
                <a href="{{ route('rutinas.index') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">📌</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Asignaciones</h3>
                        <p class="dashboard-action-card-text">Asigna rutinas a estudiantes.</p>
                    </div>
                </a>
                <a href="{{ route('admin.notices.index') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">📢</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Avisos</h3>
                        <p class="dashboard-action-card-text">Publica y gestiona anuncios.</p>
                    </div>
                </a>
                <a href="{{ route('admin.analytics') }}" class="admin-menu-card">
                    <div class="dashboard-action-card-icon">📊</div>
                    <div>
                        <h3 class="dashboard-action-card-title">Analíticas</h3>
                        <p class="dashboard-action-card-text">Revisa el comportamiento del gimnasio.</p>
                    </div>
                </a>
            </div>
        </section>

        <aside class="admin-card">
            <p class="admin-section-label">Tu Perfil</p>
            <h3 class="text-xl font-semibold text-emerald-950 mb-3">Información general</h3>
            <p class="text-slate-600 mb-6">Accede rápidamente a tu información y a los módulos más usados.</p>

            <div class="grid gap-4 mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Nombre</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Correo</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Rol</p>
                    <p class="text-base font-medium text-emerald-950">Administrador</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Miembro desde</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="grid gap-3">
                <p class="text-sm text-slate-600">Consulta los módulos de administración desde el menú principal.</p>
                <p class="text-sm text-slate-600">Este panel está orientado a mostrar solo información, no enlaces directos.</p>
            </div>
        </aside>
    </div>

    <div class="admin-card mb-8">
        <h2 class="text-xl font-semibold text-emerald-950 mb-4">Indicadores clave</h2>
        <div class="admin-stat-grid">
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Usuarios totales</p>
                <div class="dashboard-stat-value">{{ $stats['totalUsers'] }}</div>
            </div>
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Estudiantes</p>
                <div class="dashboard-stat-value">{{ $stats['totalStudents'] }}</div>
            </div>
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Máquinas</p>
                <div class="dashboard-stat-value">{{ $stats['totalMachines'] }}</div>
            </div>
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Ejercicios</p>
                <div class="dashboard-stat-value">{{ $stats['totalExercises'] }}</div>
            </div>
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Fichas de salud</p>
                <div class="dashboard-stat-value">{{ $stats['totalPhysicalInfos'] }}</div>
            </div>
            <div class="dashboard-stat-card">
                <p class="dashboard-stat-label">Actividades hoy</p>
                <div class="dashboard-stat-value">{{ $stats['activeToday'] }}</div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h2 class="text-xl font-semibold text-emerald-950 mb-4">Actividad reciente</h2>
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-emerald-50 rounded-[24px] border border-emerald-100 p-6">
                <p class="admin-section-label">Nuevos usuarios</p>
                <ul class="space-y-3 text-slate-700">
                    @forelse($recentUsers as $user)
                        <li class="rounded-2xl bg-white p-4 shadow-sm">
                            <p class="font-semibold text-emerald-950">{{ $user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            <p class="text-sm text-slate-500">Registrado el {{ $user->created_at->format('d/m/Y') }}</p>
                        </li>
                    @empty
                        <li>No hay registros de nuevos usuarios recientes.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-emerald-50 rounded-[24px] border border-emerald-100 p-6">
                <p class="admin-section-label">Fichas recientes</p>
                <ul class="space-y-3 text-slate-700">
                    @forelse($recentPhysicalInfo as $info)
                        <li class="rounded-2xl bg-white p-4 shadow-sm">
                            <p class="font-semibold text-emerald-950">{{ $info->user->name ?? 'Usuario eliminado' }}</p>
                            <p class="text-sm text-slate-500">Actualizado el {{ $info->updated_at->format('d/m/Y') }}</p>
                            <p class="text-sm text-slate-500">{{ $info->health_observations ?? 'Ficha actualizada' }}</p>
                        </li>
                    @empty
                        <li>No hay fichas recientes.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
