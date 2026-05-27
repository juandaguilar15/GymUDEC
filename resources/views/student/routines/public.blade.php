@extends('layouts.student')

@section('title', 'Explorar Rutinas Públicas')
@section('page-title', 'Rutinas Públicas')
@section('page-subtitle', 'Busca y agrega rutinas públicas a tu panel')
@section('page-actions')
    <a href="{{ route('student.routines.index') }}" class="btn-tertiary">← Mis Rutinas</a>
@endsection

@push('head')
<style>
    .filters { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-top: 1rem; margin-bottom: 1.25rem; }
    @media (max-width: 900px) { .filters { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <div class="grid gap-6">
        <section class="admin-card">
            <h2 class="text-xl font-semibold text-emerald-950">Explorar Rutinas Públicas</h2>
            <p class="text-slate-600 mt-2">Busca, filtra y revisa rutinas públicas que el administrador o estudiantes han hecho públicas. Si te interesa una, agrégala a tus rutinas.</p>

            <form method="GET" action="{{ route('student.routines.public.index') }}" class="mt-4">
                <div class="filters">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Buscar</label>
                        <input id="q" name="q" type="search" placeholder="Buscar rutinas..." value="{{ $filters['q'] ?? '' }}" class="border rounded-md w-full px-3 py-2" />
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Objetivo</label>
                        <select id="objective" name="objective" class="border rounded-md w-full px-3 py-2">
                            <option value="">Todos</option>
                            <option value="fuerza" {{ ($filters['objective'] ?? '') === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                            <option value="cardio" {{ ($filters['objective'] ?? '') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                            <option value="mixto" {{ ($filters['objective'] ?? '') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Nivel</label>
                        <select id="level" name="level" class="border rounded-md w-full px-3 py-2">
                            <option value="">Todos</option>
                            <option value="principiante" {{ ($filters['level'] ?? '') === 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ ($filters['level'] ?? '') === 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ ($filters['level'] ?? '') === 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Días por semana</label>
                        <select id="days_per_week" name="days_per_week" class="border rounded-md w-full px-3 py-2">
                            <option value="">Todos</option>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}" {{ ($filters['days_per_week'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Creador</label>
                        <select id="creator" name="creator" class="border rounded-md w-full px-3 py-2">
                            <option value="">Todos</option>
                            <option value="administrador" {{ ($filters['creator'] ?? '') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="estudiante" {{ ($filters['creator'] ?? '') === 'estudiante' ? 'selected' : '' }}>Estudiantes</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">Aplicar filtros</button>
                    </div>
                </div>
            </form>

            @if($routines->isEmpty())
                <div class="empty-state mt-4">No se encontraron rutinas públicas con los criterios seleccionados.</div>
            @else
                <div class="grid gap-4 mt-4 lg:grid-cols-2">
                    @foreach($routines as $routine)
                        <article class="page-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-emerald-950">{{ $routine->name }}</h3>
                                    <p class="text-sm text-slate-600 mt-2">Nivel: {{ ucfirst($routine->level) }} · Días: {{ $routine->days_per_week }} · {{ $routine->duration_weeks }} semanas</p>
                                    <p class="text-sm text-slate-500 mt-2">{{ \Illuminate\Support\Str::limit($routine->description, 140) }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="mb-2">
                                        <span class="badge">{{ $routine->creator_label }}</span>
                                        <span class="badge">Objetivo: {{ ucfirst($routine->objective) }}</span>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('student.routines.public.show', $routine->id) }}" class="btn-tertiary">Ver detalles</a>
                                        @if(in_array($routine->id, $myRoutineIds))
                                            <span class="btn-secondary" style="cursor:default;">En mis rutinas</span>
                                        @else
                                            <form method="POST" action="{{ route('student.routines.public.add', $routine->id) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary">Agregar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-4">{{ $routines->links() }}</div>
            @endif
        </section>
    </div>
@endsection
