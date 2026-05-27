@extends('layouts.admin')

@section('title', 'Máquinas - GymUdec')
@section('page-title', '🏋️ Máquinas del Gimnasio')
@section('page-subtitle', 'Administra el inventario de máquinas y la disponibilidad del equipo.')
@section('page-actions')
    <a href="{{ route('machines.create') }}" class="btn-primary">➕ Agregar Máquina</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="page-card">
        <form method="GET" action="{{ route('machines.index') }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_auto] mb-6">
            <div class="form-group">
                <label for="search">Buscar máquina o tipo</label>
                <input id="search" name="search" type="search" value="{{ $search ?? '' }}" placeholder="Ingresa nombre o tipo..." />
            </div>
            <div class="form-group">
                <label for="type">Filtrar por tipo</label>
                <select id="type" name="type">
                    <option value="">Todos los tipos</option>
                    <option value="cardio" {{ (isset($type) && $type === 'cardio') ? 'selected' : '' }}>Cardio</option>
                    <option value="fuerza" {{ (isset($type) && $type === 'fuerza') ? 'selected' : '' }}>Fuerza</option>
                    <option value="mixto" {{ (isset($type) && $type === 'mixto') ? 'selected' : '' }}>Mixto</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="btn-primary w-full">🔍 Buscar</button>
                <a href="{{ route('machines.index') }}" class="btn-tertiary w-full text-center">↻ Limpiar</a>
            </div>
        </form>

        @if ($machines->count() > 0)
            <div class="item-grid">
                @foreach ($machines as $machine)
                    <div class="item-card">
                        @php
                            $machineImg = null;
                            if ($machine->image_url) {
                                if (filter_var($machine->image_url, FILTER_VALIDATE_URL)) {
                                    $machineImg = $machine->image_url;
                                } elseif (file_exists(storage_path('app/public/' . $machine->image_url))) {
                                    $machineImg = asset('storage/' . $machine->image_url);
                                } elseif (file_exists(public_path($machine->image_url))) {
                                    $machineImg = asset($machine->image_url);
                                }
                            }
                        @endphp

                        @if ($machineImg)
                            <img src="{{ $machineImg }}" alt="{{ $machine->name }}" class="item-card-img" />
                        @else
                            <div class="item-card-img bg-emerald-50 flex items-center justify-center text-emerald-400">🏋️</div>
                        @endif

                        <div class="item-card-body">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="item-card-title">{{ $machine->name }}</div>
                                    <div class="item-card-meta">{{ ucfirst($machine->type) }} • <span class="status-badge {{ $machine->status ? 'status-badge--success' : 'status-badge--warning' }}">{{ $machine->status ? 'Disponible' : 'Mantenimiento' }}</span></div>
                                </div>
                                <div class="item-card-actions">
                                    <a href="{{ route('machines.show', $machine->id) }}" class="btn-tertiary">Ver</a>
                                    <a href="{{ route('machines.edit', $machine->id) }}" class="btn-tertiary">✏️</a>
                                    <form method="POST" action="{{ route('machines.destroy', $machine->id) }}" class="m-0" onsubmit="return confirm('¿Está seguro de que desea eliminar esta máquina?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Descripción eliminada para mantener tarjetas compactas; mostrar solo datos editables --}}
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($machines->hasPages())
                <div class="mt-6">{{ $machines->links() }}</div>
            @endif
        @else
            <div class="text-center py-12 text-slate-600">
                <p class="text-lg font-semibold">No se encontraron máquinas.</p>
                <p class="mt-2">Intenta ajustar los filtros o crear la primera máquina.</p>
            </div>
        @endif
    </div>
@endsection