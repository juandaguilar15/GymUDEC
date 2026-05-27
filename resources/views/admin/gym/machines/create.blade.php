@extends('layouts.admin')

@section('title', 'Nueva Máquina - GymUdec')
@section('page-title', '🆕 Nueva Máquina')
@section('page-subtitle', 'Registra una nueva máquina o equipo para el gimnasio.')
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
        <form action="{{ route('machines.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
            @csrf

            <div class="form-group">
                <label for="name">Nombre de la Máquina</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Ej: Prensa de Pierna" required />
            </div>

            <div class="form-group">
                <label for="type">Tipo</label>
                <select id="type" name="type" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="cardio" {{ old('type') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                    <option value="fuerza" {{ old('type') === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                    <option value="mixto" {{ old('type') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image_url">Imagen de la Máquina</label>
                <input id="image_url" name="image_url" type="file" accept="image/*" />
                <p class="info-text">Formatos: JPG, PNG. Máx: 2MB.</p>
            </div>

            <div class="form-group">
                <label for="status">Estado inicial</label>
                <select id="status" name="status">
                    <option value="1" {{ old('status', 1) ? 'selected' : '' }}>Disponible</option>
                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
                <p class="info-text">Si seleccionas mantenimiento, la máquina queda fuera de uso.</p>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('machines.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar Máquina</button>
            </div>
        </form>
    </div>
@endsection