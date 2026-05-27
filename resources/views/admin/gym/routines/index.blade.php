@extends('layouts.admin')

@section('title', 'Rutinas - GymUdec')
@section('page-title', '📋 Rutinas')
@section('page-subtitle', 'Gestiona las rutinas disponibles en el gimnasio.')
@section('page-actions')
    <a href="{{ route('routines.create') }}" class="btn-primary">➕ Crear Rutina</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="page-card">
        <form method="GET" action="{{ route('routines.index') }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_auto] mb-6">
            <div class="form-group">
                <label for="search">Buscar rutina</label>
                <input id="search" name="search" type="search" placeholder="Nombre, objetivo o nivel..." value="{{ $search ?? '' }}" />
            </div>
            <div class="form-group">
                <label for="status">Estado</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <option value="publica" {{ (isset($status) && $status === 'publica') ? 'selected' : '' }}>Pública</option>
                    <option value="privada" {{ (isset($status) && $status === 'privada') ? 'selected' : '' }}>Privada</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="btn-primary w-full">🔍 Buscar</button>
                <a href="{{ route('routines.index') }}" class="btn-tertiary w-full text-center">↻ Limpiar</a>
            </div>
        </form>

        @if ($routines->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Objetivo</th>
                            <th>Nivel</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routines as $routine)
                            <tr>
                                <td>{{ $routine->name }}</td>
                                <td>
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-900">{{ ucfirst($routine->objective) }}</span>
                                </td>
                                <td>{{ ucfirst($routine->level) }}</td>
                                <td>{{ $routine->duration_weeks }} semanas • {{ $routine->days_per_week }} días/semana</td>
                                <td>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $routine->status === 'publica' ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">{{ ucfirst($routine->status) }}</span>
                                </td>
                                <td class="flex flex-wrap gap-2">
                                    <a href="{{ route('routines.edit', $routine->id) }}" class="btn-tertiary">✏️ Editar</a>
                                    <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Está seguro de que desea eliminar esta rutina?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($routines->hasPages())
                <div class="mt-6">{{ $routines->links() }}</div>
            @endif
        @else
            <div class="text-center py-12 text-slate-600">
                <p class="text-lg font-semibold">No se encontraron rutinas.</p>
                <p class="mt-2">Crea una nueva rutina para empezar a entrenar.</p>
            </div>
        @endif
    </div>
@endsection