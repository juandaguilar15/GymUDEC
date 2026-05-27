@extends('layouts.admin')

@section('title', 'Editar Ejercicio - GymUdec')
@section('page-title', '✏️ Editar Ejercicio')
@section('page-subtitle', 'Actualiza los detalles del ejercicio y su máquina asociada.')
@section('page-actions')
    <a href="{{ route('exercises.index') }}" class="btn-tertiary">← Volver al listado</a>
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
        <form action="{{ route('exercises.update', $exercise->id) }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre del Ejercicio</label>
                <input id="name" name="name" type="text" value="{{ old('name', $exercise->name) }}" required />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="type">Tipo</label>
                    <select id="type" name="type" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="fuerza" {{ old('type', $exercise->type) === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="cardio" {{ old('type', $exercise->type) === 'cardio' ? 'selected' : '' }}>Cardio</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="machine_id">Máquina Asociada</label>
                    <select id="machine_id" name="machine_id">
                        <option value="">Seleccione una máquina</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}" {{ old('machine_id', $exercise->machine_id) == $machine->id ? 'selected' : '' }}>{{ $machine->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="exercise_format">Formato</label>
                    <select id="exercise_format" name="exercise_format" required>
                        <option value="series_reps" {{ old('exercise_format', $exercise->exercise_format) === 'series_reps' ? 'selected' : '' }}>Series y Repeticiones</option>
                        <option value="duration" {{ old('exercise_format', $exercise->exercise_format) === 'duration' ? 'selected' : '' }}>Duración</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="muscle_group">Grupo Muscular</label>
                    <input id="muscle_group" name="muscle_group" type="text" value="{{ old('muscle_group', $exercise->muscle_group) }}" required />
                </div>
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" required>{{ old('description', $exercise->description) }}</textarea>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="image_url">Cambiar imagen</label>
                    <input id="image_url" name="image_url" type="file" accept="image/*" />
                </div>
                <div class="form-group">
                    <label for="media_url">Cambiar video</label>
                    <input id="media_url" name="media_url" type="file" accept="video/*" />
                </div>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('exercises.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar Ejercicio</button>
            </div>
        </form>
    </div>
@endsection