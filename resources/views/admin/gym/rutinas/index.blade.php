@extends('layouts.admin')

@section('title', 'Rutinas Asignadas - GymUdec')
@section('page-title', '📤 Rutinas Asignadas')
@section('page-subtitle', 'Administra las rutinas asignadas a estudiantes.')
@section('page-actions')
    <a href="{{ route('rutinas.create') }}" class="btn-primary">➕ Asignar Rutina</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="errors">
            <ul>
                <li>{{ session('error') }}</li>
            </ul>
        </div>
    @endif

    <div class="page-card">
        <form method="GET" action="{{ route('rutinas.index') }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_auto] mb-6">
            <div class="form-group">
                <label for="search">Buscar asignación</label>
                <input id="search" name="search" type="search" placeholder="Rutina, estudiante o correo..." value="{{ $search ?? '' }}" />
            </div>
            <div class="form-group">
                <label for="status">Estado</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" {{ (isset($status) && $status === 'active') ? 'selected' : '' }}>Activas</option>
                    <option value="inactive" {{ (isset($status) && $status === 'inactive') ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="btn-primary w-full">🔍 Buscar</button>
                <a href="{{ route('rutinas.index') }}" class="btn-tertiary w-full text-center">↻ Limpiar</a>
            </div>
        </form>

        @if ($rutinas->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Rutina</th>
                            <th>Estudiante</th>
                            <th>Email</th>
                            <th>Asignada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rutinas as $rutina)
                            <tr>
                                <td>{{ $rutina->routine_name }}</td>
                                <td>{{ $rutina->student_name }}</td>
                                <td>{{ $rutina->student_email }}</td>
                                <td>{{ $rutina->created_at->format('d/m/Y') }}</td>
                                <td class="flex flex-wrap gap-2">
                                    <a href="{{ route('rutinas.edit', $rutina->id) }}" class="btn-tertiary">✏️ Editar</a>
                                    <form action="{{ route('rutinas.destroy', $rutina->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
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

            @if ($rutinas->hasPages())
                <div class="mt-6">{{ $rutinas->links() }}</div>
            @endif
        @else
            <div class="text-center py-12 text-slate-600">
                <p class="text-lg font-semibold">No se encontraron asignaciones.</p>
                <p class="mt-2">Asigna una rutina para comenzar a darle seguimiento.</p>
            </div>
        @endif
    </div>
@endsection