@extends('layouts.student')

@section('title', $routine->name . ' - Rutina Pública')
@section('page-title', $routine->name)
@section('page-subtitle', 'Detalle de rutina pública')
@section('page-actions')
    <a href="{{ route('student.routines.public.index') }}" class="btn-tertiary">← Volver</a>
    <a href="{{ route('student.routines.index') }}" class="btn-tertiary">Mis Rutinas</a>
@endsection

@push('head')
<style>
    .day-card { border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.25rem; background: #fcfcfc; margin-bottom: 1rem; }
    .exercise-item { border-top: 1px solid #e6e6e6; padding: 0.75rem 0; display: grid; grid-template-columns: 1fr auto; gap: 1rem; }
    @media (max-width: 768px) { .exercise-item { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.45fr]">
        <section class="admin-card">
            <h2 class="text-xl font-semibold text-emerald-950">{{ $routine->name }}</h2>
            <p class="text-slate-600 mt-2">Objetivo: {{ ucfirst($routine->objective) }} · Nivel: {{ ucfirst($routine->level) }}</p>
            <p class="text-slate-600">Días por semana: {{ $routine->days_per_week }} · Duración: {{ $routine->duration_weeks }} semanas</p>
            <p class="text-slate-600 mt-2">{{ $routine->description }}</p>

            <div class="mt-6">
                <h3 class="text-lg font-semibold text-emerald-950 mb-3">Días de entrenamiento</h3>
                @foreach($routine->trainingDays as $trainingDay)
                    <div class="day-card">
                        <h4 class="font-semibold text-emerald-950">{{ ucfirst($trainingDay->day_name) }}</h4>
                        @if($trainingDay->exercises->isEmpty())
                            <p class="text-slate-600 mt-2">No hay ejercicios asignados para este día.</p>
                        @else
                            @foreach($trainingDay->exercises as $exerciseItem)
                                <div class="exercise-item">
                                    <div>
                                        <div class="font-semibold text-emerald-950">{{ $exerciseItem->exercise?->name ?? 'Ejercicio no disponible' }}</div>
                                        <div class="text-sm text-slate-600">{{ $exerciseItem->exercise?->type ? ucfirst($exerciseItem->exercise->type) : 'Tipo desconocido' }}</div>
                                    </div>
                                    <div class="text-sm text-slate-600">
                                        @if($exerciseItem->exercise?->exercise_format === 'duration')
                                            <p>Duración: {{ $exerciseItem->duration ?? 'N/A' }} {{ $exerciseItem->duration_unit ?? '' }}</p>
                                        @else
                                            <p>Series: {{ $exerciseItem->sets ?? 'N/A' }}</p>
                                            <p>Reps: {{ $exerciseItem->reps ?? 'N/A' }}</p>
                                        @endif
                                        <p>Descanso: {{ $exerciseItem->rests ?? 'N/A' }} {{ $exerciseItem->rests_unit ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <span class="status-badge">{{ ucfirst($routine->status) }}</span>
                </div>
            </div>
            <div class="mt-4">
                @if($inMyRoutines)
                    <div class="text-emerald-700 font-semibold">Ya está en tus rutinas</div>
                @else
                    <form method="POST" action="{{ route('student.routines.public.add', $routine->id) }}">
                        @csrf
                        <button type="submit" class="btn-primary w-full">Agregar a mis rutinas</button>
                    </form>
                @endif
            </div>

            <div class="info-panel mt-6">
                <h4 class="font-semibold text-emerald-950">¿Qué puedes hacer?</h4>
                <p class="text-slate-600 mt-2">Esta rutina es pública; agrégala para verla desde tu panel y ejecutarla cuando quieras.</p>
            </div>
        </aside>
    </div>
@endsection
