@extends('layouts.student')

@section('title', 'Crear Rutina - GymUdec')
@section('page-title', '📋 Crear Rutina')
@section('page-subtitle', 'Define el objetivo, duración y días de entrenamiento para la rutina.')
@section('page-actions')
    <a href="{{ route('student.routines.index') }}" class="btn-tertiary">← Mis Rutinas</a>
@endsection

@push('head')
<style>
    .routine-form {
        display: grid;
        gap: 1.5rem;
    }

    .days-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.75rem;
    }

    .day-button {
        border-radius: 1rem;
        border: 1px solid #d1fae5;
        background: #ffffff;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #065f46;
        transition: all 0.2s ease;
    }

    .day-button:hover {
        border-color: #86efac;
        background: #ecfdf5;
    }

    .day-button.selected {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
    }

    .exercise-panel {
        border-radius: 1.875rem;
        border: 1px solid #d1fae5;
        background: #ecfdf5;
        padding: 1.5rem;
    }

    .exercise-item {
        border-radius: 1.5rem;
        border: 1px solid #d1fae5;
        background: #ffffff;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .exercise-fields {
        display: grid;
        gap: 1rem;
        grid-template-columns: 2fr 1fr 1fr;
    }

    .exercise-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .remove-exercise {
        border-radius: 1rem;
        background: #dc2626;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .remove-exercise:hover {
        background: #b91c1c;
    }

    .add-exercise-btn {
        border-radius: 1rem;
        background: #047857;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .add-exercise-btn:hover {
        background: #065f46;
    }

    .day-tab {
        border-radius: 1rem;
        background: #ffffff;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #065f46;
        border: 1px solid #d1fae5;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .day-tab:hover {
        background: #ecfdf5;
    }

    .day-tab.active {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
    }

    .day-content {
        display: none;
    }

    .day-content.block {
        display: block;
    }

    .day-content.hidden {
        display: none;
    }

    .info-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }
</style>
@endpush

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
        <form action="{{ route('student.routines.store') }}" method="POST" id="routineForm" class="routine-form">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="name">Nombre de la Rutina</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Ej: Rutina de Fuerza Principiante" required />
                </div>
                <div class="form-group">
                    <label for="level">Nivel</label>
                    <input id="level" name="level" type="text" value="{{ old('level') }}" placeholder="Ej: Principiante" required />
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="objective">Objetivo</label>
                    <select id="objective" name="objective" required>
                        <option value="">Seleccione un objetivo</option>
                        <option value="fuerza" {{ old('objective') === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="cardio" {{ old('objective') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="mixto" {{ old('objective') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Estado</label>
                    <select id="status" name="status" required>
                        <option value="publica" {{ old('status', 'publica') === 'publica' ? 'selected' : '' }}>Pública</option>
                        <option value="privada" {{ old('status') === 'privada' ? 'selected' : '' }}>Privada</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-group">
                    <label for="duration_weeks">Duración (semanas)</label>
                    <input id="duration_weeks" name="duration_weeks" type="number" min="1" max="52" value="{{ old('duration_weeks') }}" required />
                </div>
                <div class="form-group">
                    <label for="days_per_week">Días por semana</label>
                    <input id="days_per_week" name="days_per_week" type="number" min="1" max="7" value="{{ old('days_per_week') ?? 1 }}" required onchange="updateDaySelector()" />
                    <p class="info-text">Define cuántos días tendrá la rutina.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="exercise-panel">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-emerald-950">🏋️ Seleccionar Días de Entrenamiento</h3>
                    <p class="info-text">Selecciona los días que formarán parte de la rutina.</p>
                </div>

                <div class="days-selector" id="daysSelector">
                    @foreach ($days_of_week as $dayValue => $dayLabel)
                        <button type="button" class="day-button" data-day="{{ $dayValue }}" data-day-label="{{ $dayLabel }}" onclick="toggleDay(this, event)">{{ $dayLabel }}</button>
                    @endforeach
                </div>

                <div id="selectedTrainingDays"></div>

                <div class="form-group">
                    <label class="block text-sm font-semibold text-emerald-900">Ejercicios por Día</label>
                    <div id="dayTabs" class="flex flex-wrap gap-3 mb-4"></div>
                    <div id="dayContents"></div>
                </div>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('student.routines.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">{{ isset($routine) ? 'Guardar cambios' : 'Crear Rutina' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const daysOfWeek = @json($days_of_week);
    let selectedDays = [];

    function toggleDay(button, event) {
        event.preventDefault();
        const day = button.dataset.day;
        const daysPerWeek = parseInt(document.getElementById('days_per_week').value) || 1;

        if (button.classList.contains('selected')) {
            button.classList.remove('selected');
            selectedDays = selectedDays.filter(d => d !== day);
        } else {
            if (selectedDays.length >= daysPerWeek) {
                alert(Solo puedes seleccionar ${daysPerWeek} día(s));
                return;
            }
            button.classList.add('selected');
            selectedDays.push(day);
        }

        updateDayTabs();
    }

    function updateDayTabs() {
        const dayTabs = document.getElementById('dayTabs');
        const dayContents = document.getElementById('dayContents');
        const selectedTrainingDays = document.getElementById('selectedTrainingDays');

        dayTabs.innerHTML = '';
        dayContents.innerHTML = '';

        selectedDays.forEach((day, index) => {
            const dayLabel = daysOfWeek[day];

            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = day-tab ${index === 0 ? 'active' : ''};
            tab.textContent = dayLabel;
            tab.onclick = (e) => {
                e.preventDefault();
                switchDay(day);
            };
            dayTabs.appendChild(tab);

            const content = document.createElement('div');
            content.className = day-content ${index === 0 ? 'block' : 'hidden'};
            content.id = day-${day};
            content.innerHTML = 
                <div id="exercises-${day}"></div>
                <button type="button" class="add-exercise-btn" onclick="addExerciseToDay('${day}', event)">➕ Agregar Ejercicio a ${dayLabel}</button>
            ;
            dayContents.appendChild(content);

            if (!content.querySelector('.exercise-item')) {
                addExerciseToDay(day);
            }
        });

        selectedTrainingDays.innerHTML = selectedDays.map(d => <input type="hidden" name="training_days[]" value="${d}">").join('');
    }

    function switchDay(day) {
        document.querySelectorAll('.day-content').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.day-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.day === day);
        });
        const target = document.getElementById(day-${day});
        if (target) {
            target.classList.remove('hidden');
        }
    }

    function addExerciseToDay(day, event) {
        if (event) event.preventDefault();
        const exercisesContainer = document.getElementById(exercises-${day});
        const itemIndex = exercisesContainer.children.length;
        const uniqueId = `-${itemIndex}-${Date.now()};

        const exerciseItem = document.createElement('div');
        exerciseItem.className = 'exercise-item';
        exerciseItem.id = exercise-${uniqueId};
        exerciseItem.innerHTML = 
            <div class="exercise-header">
                <span class="font-semibold text-slate-700">Ejercicio ${itemIndex + 1}</span>
                <button type="button" class="remove-exercise" onclick="removeExercise('${uniqueId}', event)">Remover</button>
            </div>
            <div class="exercise-fields">
                <div class="form-group">
                    <label>Ejercicio</label>
                    <select name="exercises[]" class="exercise-select" required onchange="updateExerciseFieldsByFormat('${uniqueId}')">
                        <option value="" data-format="series_reps">Seleccione un ejercicio</option>
                        @foreach ($exercises as $exercise)
                            <option value="{{ $exercise->id }}" data-format="{{ $exercise->exercise_format ?? 'series_reps' }}">{{ $exercise->name }} ({{ ucfirst($exercise->type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group series-fields">
                    <label>Series</label>
                    <input type="number" name="sets[]" min="1" step="1" />
                </div>
                <div class="form-group series-fields">
                    <label>Repeticiones</label>
                    <input type="number" name="reps[]" min="1" step="1" />
                </div>
                <div class="form-group duration-fields" style="display:none;">
                    <label>Duración</label>
                    <input type="number" name="durations[]" min="1" step="1" />
                </div>
                <div class="form-group duration-fields" style="display:none;">
                    <label>Unidad</label>
                    <select name="duration_units[]">
                        <option value="segundos">Segundos</option>
                        <option value="minutos">Minutos</option>
                    </select>
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2 mt-4">
                <div class="form-group">
                    <label>Descanso</label>
                    <input type="number" name="descansos[]" min="0" step="1" />
                </div>
                <div class="form-group">
                    <label>Unidad descanso</label>
                    <select name="descansos_unidad[]">
                        <option value="segundos">Segundos</option>
                        <option value="minutos">Minutos</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="exercise_days[]" value="${day}" />
        ;

        exercisesContainer.appendChild(exerciseItem);
        updateExerciseFieldsByFormat(uniqueId);
        updateRemoveButtons();
    }

    function updateExerciseFieldsByFormat(uniqueId) {
        const container = document.getElementById(exercise-${uniqueId});
        if (!container) return;
        const select = container.querySelector('.exercise-select');
        if (!select) return;
        const format = select.selectedOptions[0]?.dataset.format || 'series_reps';
        container.querySelectorAll('.series-fields').forEach(el => el.style.display = format === 'series_reps' ? 'grid' : 'none');
        container.querySelectorAll('.duration-fields').forEach(el => el.style.display = format === 'duration' ? 'grid' : 'none');
    }

    function removeExercise(uniqueId, event) {
        event.preventDefault();
        document.getElementById(exercise-${uniqueId})?.remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.exercise-item');
        items.forEach((item, index) => {
            const button = item.querySelector('.remove-exercise');
            if (button) button.style.display = items.length > 1 ? 'inline-flex' : 'none';
        });
    }

    function updateDaySelector() {
        updateDayTabs();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateRemoveButtons();
    });

</script>
@endpush
