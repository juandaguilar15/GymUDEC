@extends('layouts.student')

@section('title', 'Mi Panel - GymUdec')
@section('page-title', 'Panel Estudiante')
@section('page-subtitle', 'Rutinas, avisos y permisos claros en un solo lugar.')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="grid gap-6 lg:grid-cols-[2.3fr_1fr] mb-8">
            <section class="admin-card">
                <p class="admin-section-label">Tu panel</p>
                <h2 class="text-3xl font-bold text-emerald-950 mb-4">Bienvenido, {{ auth()->user()->name }}</h2>
                <p class="text-slate-600 mb-6">Desde aquí accedes a tus rutinas, avisos y tu ficha física. Todo está ahora mejor organizado para que no quede espacio desperdiciado.</p>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
                    <a href="{{ route('student.routines.index') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">🏋️</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Mis rutinas</h3>
                            <p class="dashboard-action-card-text">Revisa tus entrenamientos activos y sigue tu progreso.</p>
                        </div>
                    </a>
                    <a href="{{ route('student.notices.index') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">📝</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Avisos</h3>
                            <p class="dashboard-action-card-text">Lee mensajes importantes del gimnasio rápidamente.</p>
                        </div>
                    </a>
                    <a href="{{ route('student.my-physical-info') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">🩺</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Ficha física</h3>
                            <p class="dashboard-action-card-text">Confirma tu permiso y estado de salud actual.</p>
                        </div>
                    </a>
                    @if(!empty($canCreate) && $canCreate)
                        <a href="{{ route('student.routines.create') }}" class="action-card group">
                            <div class="dashboard-action-card-icon">✍️</div>
                            <div>
                                <h3 class="dashboard-action-card-title">Crear rutina</h3>
                                <p class="dashboard-action-card-text">Tienes permiso para armar tu propia rutina.</p>
                            </div>
                        </a>
                    @endif
                </div>
            </section>

            @php $physicalInfo = $user->physicalInfo; @endphp
            <aside class="admin-card self-start">
                <p class="admin-section-label">Tu perfil</p>
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-50 p-5 mx-auto max-w-md">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 rounded-full bg-emerald-600 grid place-items-center text-white text-2xl font-bold">{{ strtoupper(substr($user->name,0,1)) }}</div>
                        <h3 class="mt-3 text-lg font-semibold text-emerald-950">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500 break-words">{{ $user->email }}</p>

                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">Estudiante</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">{{ ucfirst($permisos ?? 'Desconocido') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3 text-center">
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <div class="text-xs text-slate-500 uppercase">Rutinas</div>
                            <div class="text-lg font-semibold text-emerald-950">{{ $routineCount ?? 0 }}</div>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <div class="text-xs text-slate-500 uppercase">Avisos</div>
                            <div class="text-lg font-semibold text-emerald-950">{{ $notices->count() }}</div>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <div class="text-xs text-slate-500 uppercase">Ficha</div>
                            <div class="text-lg font-semibold text-emerald-950">{{ $physicalInfo ? 'Registrada' : 'Pendiente' }}</div>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-slate-600">
                        @if($physicalInfo)
                            Última actualización: <strong class="text-emerald-950">{{ $physicalInfo->updated_at?->format('d M Y') ?? '—' }}</strong>
                        @else
                            No hay ficha registrada.
                        @endif
                    </div>

                    <div class="mt-4 flex justify-center gap-3">
                        <a href="{{ route('student.my-physical-info') }}" class="btn-tertiary">Ver ficha</a>
                    </div>
                </div>
            </aside>
        </div>

        <div class="admin-card mb-8">
            <p class="admin-section-label">Información clave</p>
            <div class="admin-stat-grid">
                <x-dashboard-stat-card label="Rutinas activas" :value="$routineCount ?? 0" />
                <x-dashboard-stat-card label="Avisos activos" :value="$notices->count()" />
                <x-dashboard-stat-card label="Ficha física" :value="optional($user->physicalInfo)->exists ? 'Registrada' : 'Pendiente'" />
                <x-dashboard-stat-card label="Permiso" :value="ucfirst($permisos ?? 'Desconocido')" />
            </div>
        </div>

        @if(empty($canCreate) || !$canCreate)
            <section class="admin-card">
                <p class="admin-section-label">Permiso de rutina</p>
                <p class="text-slate-600">No tienes permiso para crear tus propias rutinas. Si necesitas una nueva rutina, tu enfermería o administrador debe asignarla.</p>
            </section>
        @endif
    </div>
@endsection
