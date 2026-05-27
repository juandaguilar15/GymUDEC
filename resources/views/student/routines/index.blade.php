@extends('layouts.student')

@section('title', 'Mis Rutinas - GymUdec')
@section('page-title', 'Mis Rutinas')
@section('page-subtitle', 'Gestiona tus rutinas de forma clara y sin elementos administrativos innecesarios.')
@section('page-actions')
    @if($canCreate)
        <a href="{{ route('student.routines.create') }}" class="btn-primary">✍️ Crear Rutina</a>
    @endif
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
        <section class="admin-card space-y-6">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-dashboard-stat-card :label="'Rutinas creadas'" :value="$myRoutines->count()" />
                <x-dashboard-stat-card :label="'Rutinas asignadas'" :value="$assignedRoutines->count()" />
                <x-dashboard-stat-card :label="'Permiso'" :value="\Illuminate\Support\Str::ucfirst($permisos ?? 'Desconocido')" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <x-action-card :href="route('student.routines.index')" icon="list" title="Mis Rutinas" text="Revisa tus rutinas creadas y asignadas en un solo lugar." />
                <x-action-card :href="route('student.routines.public.index')" icon="globe" title="Rutinas Públicas" text="Explora rutinas compartidas y agrégalas a tu entrenamiento." />
                <x-action-card :href="route('student.notices.index')" icon="notice" title="Avisos" text="Mantente al tanto de las notificaciones y mensajes importantes." />
                @if($canCreate)
                    <x-action-card :href="route('student.routines.create')" icon="pen" title="Crear Rutina" text="Diseña tu propia rutina con la interfaz administrativa." />
                @endif
            </div>

            @if($myRoutines->isNotEmpty())
                <div>
                    <h2 class="text-xl font-semibold text-emerald-950 mb-4">Mis rutinas creadas</h2>
                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach($myRoutines as $routine)
                            <article class="page-card transition hover:-translate-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-emerald-950">{{ $routine->name }}</h3>
                                        <p class="text-sm text-slate-600 mt-2">Objetivo: {{ ucfirst($routine->objective) }} · Nivel: {{ ucfirst($routine->level) }}</p>
                                    </div>
                                    <div class="space-y-2 text-right">
                                        <span class="status-badge {{ $routine->status === 'privada' ? 'status-badge--danger' : 'status-badge--success' }}">{{ ucfirst($routine->status) }}</span>
                                        <span class="status-badge status-badge--warning">Creada por ti</span>
                                    </div>
                                </div>
                                <div class="mt-4 grid gap-3 text-sm text-slate-600">
                                    <p>Duración: {{ $routine->duration_weeks }} semana(s) · Días por semana: {{ $routine->days_per_week }}</p>
                                    <p>Días registrados: {{ $routine->trainingDays->count() }} · Ejercicios: {{ $routine->trainingDays->flatMap->exercises->count() }}</p>
                                    <p>{{ Illuminate\Support\Str::limit($routine->description, 120) }}</p>
                                </div>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="{{ route('student.routines.show', $routine->id) }}" class="btn-tertiary">Ver</a>
                                    <a href="{{ route('student.routines.execute', $routine->id) }}" class="btn-secondary">Ejecutar</a>
                                    <a href="{{ route('student.routines.edit', $routine->id) }}" class="btn-primary">Editar</a>
                                    <form action="{{ route('student.routines.destroy', $routine->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Seguro que deseas eliminar esta rutina?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-tertiary" style="border-color:#e74c3c;color:#e74c3c;">Eliminar</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($assignedRoutines->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-emerald-950 mb-4">Rutinas asignadas</h2>
                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach($assignedRoutines as $routine)
                            <article class="page-card transition hover:-translate-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-emerald-950">{{ $routine->name }}</h3>
                                        <p class="text-sm text-slate-600 mt-2">Objetivo: {{ ucfirst($routine->objective) }} · Nivel: {{ ucfirst($routine->level) }}</p>
                                    </div>
                                    <div class="space-y-2 text-right">
                                        <span class="status-badge {{ $routine->status === 'privada' ? 'status-badge--danger' : 'status-badge--success' }}">{{ ucfirst($routine->status) }}</span>
                                        <span class="status-badge status-badge--success">Asignada por admin</span>
                                    </div>
                                </div>
                                <div class="mt-4 grid gap-3 text-sm text-slate-600">
                                    <p>Duración: {{ $routine->duration_weeks }} semana(s) · Días por semana: {{ $routine->days_per_week }}</p>
                                    <p>Días registrados: {{ $routine->trainingDays->count() }} · Ejercicios: {{ $routine->trainingDays->flatMap->exercises->count() }}</p>
                                    <p>{{ Illuminate\Support\Str::limit($routine->description, 120) }}</p>
                                </div>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="{{ route('student.routines.show', $routine->id) }}" class="btn-tertiary">Ver detalles</a>
                                    <a href="{{ route('student.routines.execute', $routine->id) }}" class="btn-secondary">Ejecutar</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <aside class="admin-card space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-emerald-950">Tu estado actual</h2>
                <p class="text-sm text-slate-600 mt-2">Mantén un resumen rápido de tus permisos y tu método de entrenamiento.</p>
            </div>
            <div class="grid gap-4">
                <x-dashboard-stat-card :label="'Permiso'" :value="\Illuminate\Support\Str::ucfirst($permisos ?? 'Desconocido')" />
                <x-dashboard-stat-card :label="'Rutinas totales'" :value="$routines->count()" />
                <x-dashboard-stat-card :label="'Acciones disponibles'" :value="$canCreate ? 'Crear y editar' : 'Solo ver'" />
            </div>
            @if($canCreate)
                <div class="grid gap-3">
                    <a href="{{ route('student.routines.create') }}" class="btn-primary">✍️ Crear nueva rutina</a>
                    <a href="{{ route('student.routines.public.index') }}" class="btn-secondary">🌐 Ver rutinas públicas</a>
                </div>
            @endif
        </aside>
    </div>
@endsection
