@extends('layouts.admin')

@section('title', 'Ver Máquina - GymUdec')
@section('page-title', 'Máquina')
@section('page-subtitle', 'Detalle de la máquina seleccionada')
@section('page-actions')
    <a href="{{ route('machines.index') }}" class="btn-tertiary">← Volver</a>
    <a href="{{ route('machines.edit', $machine->id) }}" class="btn-primary">✏️ Editar</a>
@endsection

@section('content')
    <div class="page-card">
        <div class="grid gap-6 lg:grid-cols-[1fr_2fr]">
            <div class="flex flex-col items-center">
                @php
                    $img = null;
                    if ($machine->image_url) {
                        if (filter_var($machine->image_url, FILTER_VALIDATE_URL)) {
                            $img = $machine->image_url;
                        } elseif (file_exists(storage_path('app/public/' . $machine->image_url))) {
                            $img = asset('storage/' . $machine->image_url);
                        } elseif (file_exists(public_path($machine->image_url))) {
                            $img = asset($machine->image_url);
                        }
                    }
                @endphp

                @if ($img)
                    <img src="{{ $img }}" alt="{{ $machine->name }}" class="w-48 h-48 object-cover rounded-2xl" />
                @else
                    <div class="w-48 h-48 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-400 text-4xl">🏋️</div>
                @endif

                <div class="mt-4 text-center">
                    <p class="text-sm text-slate-500">Creada el {{ $machine->created_at->format('d/m/Y') }}</p>
                    <p class="text-sm text-slate-500">Actualizada el {{ $machine->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-semibold text-emerald-950">{{ $machine->name }}</h2>
                <p class="text-sm text-slate-600 mt-2">Tipo: <strong>{{ ucfirst($machine->type) }}</strong></p>
                <p class="text-sm text-slate-600 mt-1">Estado: <span class="status-badge {{ $machine->status ? 'status-badge--success' : 'status-badge--warning' }}">{{ $machine->status ? 'Disponible' : 'Mantenimiento' }}</span></p>

                @if ($machine->exercises && $machine->exercises->count())
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-emerald-950 mb-3">Ejercicios asociados ({{ $machine->exercises->count() }})</h3>
                        <ul class="space-y-2">
                            @foreach ($machine->exercises as $exercise)
                                <li class="rounded-lg bg-emerald-50 p-3">
                                    <a href="{{ route('exercises.show', $exercise->id) }}" class="font-semibold text-emerald-900">{{ $exercise->name }}</a>
                                    <div class="text-sm text-slate-600">Formato: {{ $exercise->exercise_format === 'duration' ? 'Duración' : 'Series y repeticiones' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
