from pathlib import Path

files = {
    r'c:\Users\juand\Documents\workspace\GymUDEC\resources\views\admin\gym\machines\index.blade.php': '''@extends('layouts.admin')

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
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($machines as $machine)
                            <tr>
                                <td>{{ $machine->name }}</td>
                                <td>{{ ucfirst($machine->type) }}</td>
                                <td>
                                    <span class="status-badge {{ $machine->status ? 'status-badge--success' : 'status-badge--warning' }}">
                                        {{ $machine->status ? 'Disponible' : 'Mantenimiento' }}
                                    </span>
                                </td>
                                <td class="flex flex-wrap gap-2">
                                    <a href="{{ route('machines.edit', $machine->id) }}" class="btn-tertiary">✏️ Editar</a>
                                    <form method="POST" action="{{ route('machines.destroy', $machine->id) }}" class="m-0" onsubmit="return confirm('¿Está seguro de que desea eliminar esta máquina?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">🗑️ Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($machines->hasPages())
                <div class="mt-6">
                    {{ $machines->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12 text-slate-600">
                <p class="text-lg font-semibold">No se encontraron máquinas.</p>
                <p class="mt-2">Intenta ajustar los filtros o crear la primera máquina.</p>
            </div>
        @endif
    </div>
@endsection'''
}

for path, content in files.items():
    Path(path).write_text(content, encoding='utf-8')
