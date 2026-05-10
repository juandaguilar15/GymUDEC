@extends('layouts.nurse')

@section('title', 'Información Física - GymUdec')

@section('page-title', '📊 Información Física del Estudiante')

@section('page-subtitle', 'Registra y actualiza los datos físicos del estudiante de forma clara y ordenada.')

@section('content')
    <div class="page-card">
        <h2>Información Física</h2>
        <p>Completa los datos del estudiante y guarda su información física en el sistema.</p>

        <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #F8B803; border-radius: 5px; margin-bottom: 25px;">
            <h3 style="color: #1B5E46; font-size: 14px; margin-bottom: 5px; text-transform: uppercase;">Estudiante</h3>
            <p><strong>{{ $user->name }}</strong></p>
            <p style="font-size: 13px; color: #666;">{{ $user->email }}</p>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="age">Edad <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="age" name="age" min="15" max="100"
                           value="{{ $physicalInfo ? number_format($physicalInfo->age, 0) : old('age') }}" required>
                </div>

                <div class="form-group">
                    <label for="gender">Género <span style="color: #e74c3c;">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino" {{ ($physicalInfo->gender ?? old('gender')) === 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ ($physicalInfo->gender ?? old('gender')) === 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ ($physicalInfo->gender ?? old('gender')) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Fecha de Nacimiento <span style="color: #e74c3c;">*</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth"
                       value="{{ $physicalInfo ? $physicalInfo->date_of_birth->format('Y-m-d') : old('date_of_birth') }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="height">Altura (metros) <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="height" name="height" step="0.01" min="1" max="3"
                           placeholder="1.75" value="{{ $physicalInfo ? number_format($physicalInfo->height, 2) : old('height') }}" required>
                    <p class="info-text">Ejemplo: 1.75</p>
                </div>

                <div class="form-group">
                    <label for="weight">Peso (kg) <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="weight" name="weight" step="0.1" min="20" max="300"
                           placeholder="75.5" value="{{ $physicalInfo ? number_format($physicalInfo->weight, 1) : old('weight') }}" required>
                    <p class="info-text">Ejemplo: 75.5</p>
                </div>
            </div>

            <div class="form-group">
                <label for="condition">Condición Médica (Opcional)</label>
                <textarea id="condition" name="condition"
                          placeholder="Describe cualquier condición médica, lesión o alergia">{{ $physicalInfo->condition ?? old('condition') }}</textarea>
                <p class="info-text">Ejemplo: Alergia a penicilina, dolor lumbar crónico</p>
            </div>

            <div class="form-group">
                <label for="recommendation">Recomendación (Opcional)</label>
                <textarea id="recommendation" name="recommendation"
                          placeholder="Notas y recomendaciones personalizadas">{{ $physicalInfo->recommendation ?? old('recommendation') }}</textarea>
                <p class="info-text">Ejemplo: Evitar ejercicios de impacto, realizar calentamiento extra</p>
            </div>

            <div class="form-group">
                <label for="permisos">Permisos para Crear Rutinas <span style="color: #e74c3c;">*</span></label>
                <select id="permisos" name="permisos" required>
                    <option value="">Selecciona una opción</option>
                    <option value="libre" {{ ($physicalInfo->permisos ?? old('permisos')) === 'libre' ? 'selected' : '' }}>Libre (El estudiante puede crear sus propias rutinas)</option>
                    <option value="limitado" {{ ($physicalInfo->permisos ?? old('permisos')) === 'limitado' ? 'selected' : '' }}>Limitado (Solo el admin puede asignar rutinas)</option>
                </select>
                <p class="info-text">Selecciona según el estado físico y experiencia del estudiante en el gimnasio</p>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">💾 Guardar Información</button>
                <a href="{{ route('nurse.search-student') }}" class="btn btn-secondary" style="flex: 1;">← Volver</a>
            </div>
        </form>
    </div>
@endsection
