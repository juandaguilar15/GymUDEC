@extends('layouts.student')

@section('title', 'Detalle Rutina - GymUdec')
@section('page-title', $routine->name)
@section('page-subtitle', 'Detalle completo de la rutina y su ejecución disponible para estudiantes.')
@section('page-actions')
    <a href="{{ route('student.routines.index') }}" class="btn-tertiary">← Mis Rutinas</a>
    @if($routine->trainingDays->isNotEmpty())
        <a href="{{ route('student.routines.execute', $routine->id) }}" class="btn-primary">Ejecutar Rutina</a>
    @endif
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.5fr_0.7fr]">
        <section class="admin-card space-y-6">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-dashboard-stat-card :label="'Objetivo'" :value="\Illuminate\Support\Str::ucfirst($routine->objective)" />
                <x-dashboard-stat-card :label="'Nivel'" :value="\Illuminate\Support\Str::ucfirst($routine->level)" />
                <x-dashboard-stat-card :label="'Duración'" :value="$routine->duration_weeks . ' semana(s)'" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="status-panel">
                    <p class="text-sm text-slate-600">Estado</p>
                    <h3 class="text-lg font-semibold text-emerald-950">{{ ucfirst($routine->status) }}</h3>
                </div>
                <div class="status-panel">
                    <p class="text-sm text-slate-600">Días por semana</p>
                    <h3 class="text-lg font-semibold text-emerald-950">{{ $routine->days_per_week }}</h3>
                </div>
            </div>

            <div class="page-card">
                <h2 class="text-xl font-semibold text-emerald-950 mb-3">Descripción</h2>
                <p class="text-slate-600 leading-relaxed">{{ $routine->description }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-emerald-950 mb-4">Días de entrenamiento</h2>
                @forelse($routine->trainingDays->sortBy('day_order') as $trainingDay)
                    <article class="page-card">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-emerald-950">{{ ucfirst($trainingDay->day_name) }}</h3>
                                <p class="text-sm text-slate-600">Ejercicios: {{ $trainingDay->exercises->count() }}</p>
                            </div>
                            <span class="status-badge status-badge--success">Día activo</span>
                        </div>

                        @if($trainingDay->exercises->isEmpty())
                            <p class="text-slate-600 mt-4">Este día no tiene ejercicios registrados.</p>
                        @else
                            <div class="mt-4 grid gap-4">
                                @foreach($trainingDay->exercises as $exerciseItem)
                                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                            <div>
                                                <h4 class="font-semibold text-emerald-950">{{ $exerciseItem->exercise?->name ?? 'Ejercicio no disponible' }}</h4>
                                                <p class="text-sm text-slate-600">{{ $exerciseItem->exercise?->type ? ucfirst($exerciseItem->exercise->type) : 'Tipo desconocido' }} · {{ $exerciseItem->exercise?->machine?->name ?? 'Máquina no asignada' }}</p>
                                            </div>
                                            <div class="text-right text-sm text-slate-500">
                                                <p>{{ $exerciseItem->exercise?->exercise_format === 'duration' ? 'Formato: duración' : 'Formato: series/reps' }}</p>
                                                <p>Descanso: {{ $exerciseItem->rests ?? 'N/A' }} {{ $exerciseItem->rests_unit ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                            @if($exerciseItem->exercise?->exercise_format === 'duration')
                                                <div>
                                                    <p class="text-sm text-slate-500">Duración</p>
                                                    <p class="font-semibold text-slate-700">{{ $exerciseItem->duration ?? 'N/A' }} {{ $exerciseItem->duration_unit ?? '' }}</p>
                                                </div>
                                            @else
                                                <div>
                                                    <p class="text-sm text-slate-500">Series</p>
                                                    <p class="font-semibold text-slate-700">{{ $exerciseItem->sets ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-slate-500">Reps</p>
                                                    <p class="font-semibold text-slate-700">{{ $exerciseItem->reps ?? 'N/A' }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="page-card">
                        <p class="text-slate-600">No hay días registrados para esta rutina.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="admin-card space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-emerald-950">Detalles rápidos</h2>
                <p class="text-slate-600 mt-2">Resumen de parámetros y acceso rápido para crear o editar.</p>
            </div>
            <div class="grid gap-3">
                <x-dashboard-stat-card :label="'Permiso'" :value="\Illuminate\Support\Str::ucfirst($permisos ?? 'Desconocido')" />
                <x-dashboard-stat-card :label="'Días totales'" :value="$routine->trainingDays->count()" />
                <x-dashboard-stat-card :label="'Ejercicios totales'" :value="$routine->trainingDays->flatMap->exercises->count()" />
            </div>
            <div class="grid gap-3">
                <a href="{{ route('student.routines.edit', $routine->id) }}" class="btn-primary">Editar rutina</a>
                <a href="{{ route('student.routines.index') }}" class="btn-tertiary">Volver al listado</a>
            </div>
        </aside>
    </div>
@endsection
