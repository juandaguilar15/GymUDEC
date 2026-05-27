@extends('layouts.admin')

@section('title', 'Editar Máquina - GymUdec')
@section('page-title', '✏️ Editar Máquina')
@section('page-subtitle', 'Actualiza los datos de la máquina y su disponibilidad.')
@section('page-actions')
    <a href="{{ route('machines.index') }}" class="btn-tertiary">← Volver al listado</a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-card">
        <form action="{{ route('machines.update', $machine->id) }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre de la Máquina</label>
                <input id="name" name="name" type="text" value="{{ old('name', $machine->name) }}" placeholder="Ej: Bandas Elásticas" required />
            </div>

            <div class="form-group">
                <label for="type">Tipo</label>
                <select id="type" name="type" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="cardio" {{ old('type', $machine->type) === 'cardio' ? 'selected' : '' }}>Cardio</option>
                    <option value="fuerza" {{ old('type', $machine->type) === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                    <option value="mixto" {{ old('type', $machine->type) === 'mixto' ? 'selected' : '' }}>Mixto</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image_url">Cambiar imagen</label>
                <input id="image_url" name="image_url" type="file" accept="image/*" />
                <p class="info-text">Deja vacío para conservar la imagen actual.</p>
            </div>

            <div class="form-group">
                <label for="status">Estado</label>
                <select id="status" name="status">
                    <option value="1" {{ old('status', $machine->status) ? 'selected' : '' }}>Disponible</option>
                    <option value="0" {{ old('status', $machine->status) ? '' : 'selected' }}>Mantenimiento</option>
                </select>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('machines.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
@endsection