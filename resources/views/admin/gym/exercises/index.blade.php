@extends('layouts.admin')

@section('title', 'Ejercicios - GymUdec')
@section('page-title', '💪 Ejercicios del Gimnasio')
@section('page-subtitle', 'Administra ejercicios, asignaciones y detalles de entrenamiento.')
@section('page-actions')
    <a href="{{ route('exercises.create') }}" class="btn-primary">➕ Agregar Ejercicio</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="page-card">
        <form method="GET" action="{{ route('exercises.index') }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_1fr_auto] mb-6">
            <div class="form-group">
                <label for="search">Buscar ejercicio</label>
                <input id="search" name="search" type="search" placeholder="Nombre, tipo o máquina..." value="{{ $search ?? '' }}" />
            </div>
            <div class="form-group">
                <label for="type">Tipo</label>
                <select id="type" name="type">
                    <option value="">Todos</option>
                    <option value="cardio" {{ (isset($type) && $type === 'cardio') ? 'selected' : '' }}>Cardio</option>
                    <option value="fuerza" {{ (isset($type) && $type === 'fuerza') ? 'selected' : '' }}>Fuerza</option>
                </select>
            </div>
            <div class="form-group">
                <label for="machine">Máquina</label>
                <select id="machine" name="machine_id">
                    <option value="">Todas</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" {{ (isset($machine_id) && $machine_id == $machine->id) ? 'selected' : '' }}>{{ $machine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="btn-primary w-full">🔍 Buscar</button>
                <a href="{{ route('exercises.index') }}" class="btn-tertiary w-full text-center">↻ Limpiar</a>
            </div>
        </form>

        @if ($exercises->count() > 0)
            <div class="item-grid">
                @foreach ($exercises as $exercise)
                    <div class="item-card">
                        @php
                            $exerciseImg = null;
                            if ($exercise->image_url) {
                                if (filter_var($exercise->image_url, FILTER_VALIDATE_URL)) {
                                    $exerciseImg = $exercise->image_url;
                                } elseif (file_exists(storage_path('app/public/' . $exercise->image_url))) {
                                    $exerciseImg = asset('storage/' . $exercise->image_url);
                                } elseif (file_exists(public_path($exercise->image_url))) {
                                    $exerciseImg = asset($exercise->image_url);
                                }
                            }
                        @endphp

                        @if ($exerciseImg)
                            <img src="{{ $exerciseImg }}" alt="{{ $exercise->name }}" class="item-card-img" />
                        @else
                            <div class="item-card-img bg-emerald-50 flex items-center justify-center text-emerald-400">🏃</div>
                        @endif

                        <div class="item-card-body">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="item-card-title">{{ $exercise->name }}</div>
                                    <div class="item-card-meta">
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-900">{{ ucfirst($exercise->type) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-card-actions">
                                <a href="{{ route('exercises.show', $exercise->id) }}" class="btn-tertiary">Ver</a>
                                <a href="{{ route('exercises.edit', $exercise->id) }}" class="btn-tertiary">Editar</a>
                                <form action="{{ route('exercises.destroy', $exercise->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Está seguro de que desea eliminar este ejercicio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($exercises->hasPages())
                <div class="mt-6">{{ $exercises->links() }}</div>
            @endif
        @else
            <div class="text-center py-12 text-slate-600">
                <p class="text-lg font-semibold">No hay ejercicios registrados.</p>
                <p class="mt-2">Usa el botón «Agregar Ejercicio» para crear uno nuevo.</p>
            </div>
        @endif
    </div>
@endsection