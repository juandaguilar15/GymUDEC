@extends('layouts.nurse')

@section('title', 'Información Física - GymUdec')

@section('page-title', '📊 Información Física del Estudiante')

@section('page-subtitle', 'Registra y actualiza los datos físicos del estudiante de forma clara y ordenada.')

@section('content')
    <div class="page-messaging">
        <strong>Editar información física:</strong> Completa todos los campos obligatorios (*). Revisa las condiciones médicas y permisos antes de guardar.
    </div>

    <div class="page-card">
        <div class="flex flex-col gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-emerald-900">✏️ Editar Información Física</h2>
                <p class="text-slate-600">Actualiza los datos del estudiante con precisión médica.</p>
            </div>

            <div class="rounded-3xl bg-slate-50 border border-amber-100 p-5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-900 mb-2">Estudiante</h3>
                <p class="text-base font-semibold text-slate-900">{{ $user->name }}</p>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('nurse.save-info', ['email' => $user->email]) }}" method="POST">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="age">Edad <span class="text-rose-600">*</span></label>
                    <input type="number" id="age" name="age" min="15" max="100" value="{{ $physicalInfo ? number_format($physicalInfo->age, 0) : old('age') }}" required>
                </div>

                <div class="form-group">
                    <label for="gender">Género <span class="text-rose-600">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino" {{ ($physicalInfo->gender ?? old('gender')) === 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ ($physicalInfo->gender ?? old('gender')) === 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ ($physicalInfo->gender ?? old('gender')) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Fecha de Nacimiento <span class="text-rose-600">*</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $physicalInfo ? $physicalInfo->date_of_birth->format('Y-m-d') : old('date_of_birth') }}" required>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="height">Altura (metros) <span class="text-rose-600">*</span></label>
                    <input type="number" id="height" name="height" step="0.01" min="1" max="3" placeholder="1.75" value="{{ $physicalInfo ? number_format($physicalInfo->height, 2) : old('height') }}" required>
                    <p class="info-text">Ejemplo: 1.75</p>
                </div>

                <div class="form-group">
                    <label for="weight">Peso (kg) <span class="text-rose-600">*</span></label>
                    <input type="number" id="weight" name="weight" step="0.1" min="20" max="300" placeholder="75.5" value="{{ $physicalInfo ? number_format($physicalInfo->weight, 1) : old('weight') }}" required>
                    <p class="info-text">Ejemplo: 75.5</p>
                </div>
            </div>

            <div class="form-group">
                <label for="condition">Condición Médica (Opcional)</label>
                <textarea id="condition" name="condition" placeholder="Describe cualquier condición médica, lesión o alergia">{{ $physicalInfo->condition ?? old('condition') }}</textarea>
                <p class="info-text">Ejemplo: Alergia a penicilina, dolor lumbar crónico</p>
            </div>

            <div class="form-group">
                <label for="recommendation">Recomendación (Opcional)</label>
                <textarea id="recommendation" name="recommendation" placeholder="Notas y recomendaciones personalizadas">{{ $physicalInfo->recommendation ?? old('recommendation') }}</textarea>
                <p class="info-text">Ejemplo: Evitar ejercicios de impacto, realizar calentamiento extra</p>
            </div>

            <div class="form-group">
                <label for="permisos">Permisos para Crear Rutinas <span class="text-rose-600">*</span></label>
                <select id="permisos" name="permisos" required>
                    <option value="">Selecciona una opción</option>
                    <option value="libre" {{ ($physicalInfo->permisos ?? old('permisos')) === 'libre' ? 'selected' : '' }}>Libre (El estudiante puede crear sus propias rutinas)</option>
                    <option value="limitado" {{ ($physicalInfo->permisos ?? old('permisos')) === 'limitado' ? 'selected' : '' }}>Limitado (Solo el admin puede asignar rutinas)</option>
                </select>
                <p class="info-text">Selecciona según el estado físico y experiencia del estudiante en el gimnasio</p>
            </div>

            <div class="flex flex-wrap gap-4 mt-8">
                <button type="submit" class="btn-primary">💾 Guardar Cambios</button>
                <a href="{{ route('nurse.search-student') }}" class="btn-tertiary">← Cancelar y Volver</a>
            </div>
        </form>
    </div>
@endsection
