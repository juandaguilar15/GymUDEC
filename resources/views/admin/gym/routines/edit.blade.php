<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rutina - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #1B5E46;
            font-size: 24px;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 3px rgba(27, 94, 70, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .errors {
            margin-bottom: 20px;
        }
        
        .errors ul {
            list-style: none;
            padding: 0;
        }
        
        .errors li {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 8px;
            border-left: 4px solid #f5c6cb;
            font-size: 14px;
        }
        
        .exercises-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #F8B803;
        }
        
        .exercise-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        
        .exercise-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .remove-exercise {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .remove-exercise:hover {
            background: #c0392b;
        }
        
        .exercise-select {
            margin-bottom: 10px;
        }
        
        .duracion-input {
            margin-bottom: 0;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 30px;
        }
        
        .button-row {
            display: flex;
            gap: 10px;
        }
        
        .submit-btn {
            flex: 1;
            padding: 12px;
            background: #F8B803;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #e6a700;
        }
        
        .back-btn {
            flex: 1;
            padding: 12px;
            background: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-btn:hover {
            background: #d0d0d0;
        }
        
        /* Estilos para selector de días */
        .days-selector {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .day-button {
            padding: 10px;
            background: #f5f5f5;
            color: #666;
            border: 2px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
        }
        
        .day-button:hover {
            border-color: #1B5E46;
            background: #f0f0f0;
        }
        
        .day-button.selected {
            background: #1B5E46;
            color: white;
            border-color: #1B5E46;
        }
        
        .day-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .day-tab {
            padding: 10px 15px;
            background: #f5f5f5;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .day-tab:hover {
            background: #f0f0f0;
        }
        
        .day-tab.active {
            border-bottom-color: #1B5E46;
            color: #1B5E46;
            background: white;
        }
        
        .day-content {
            display: none;
            animation: slideIn 0.3s ease;
        }
        
        .day-content.active {
            display: block;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 15px;
            margin-top: 20px;
        }
        
        .exercise-fields {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
        }
        
        .delete-btn {
            padding: 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .delete-btn:hover {
            background: #c0392b;
        }
        
        .add-exercise-btn {
            background: #1B5E46;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }
        
        .add-exercise-btn:hover {
            background: #2a7a5e;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                margin-top: 20px;
            }
            
            .button-row {
                flex-direction: column;
            }
            
            .days-selector {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .exercise-fields {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .days-selector {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .day-tabs {
                flex-direction: column;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Rutina</h1>
        
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('routines.update', $routine->id) }}" method="POST" id="routineForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nombre de la Rutina <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Ej: Rutina de Fuerza Principiante" 
                           value="{{ $routine->name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="level">Nivel <span class="required">*</span></label>
                    <input type="text" id="level" name="level" placeholder="Ej: Principiante, Intermedio, Avanzado" 
                           value="{{ $routine->level }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="objective">Objetivo <span class="required">*</span></label>
                    <select id="objective" name="objective" required>
                        <option value="">-- Selecciona un objetivo --</option>
                        <option value="fuerza" {{ $routine->objective === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="cardio" {{ $routine->objective === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="mixto" {{ $routine->objective === 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Estado <span class="required">*</span></label>
                    <select id="status" name="status" required>
                        <option value="publica" {{ $routine->status === 'publica' ? 'selected' : '' }}>Pública</option>
                        <option value="privada" {{ $routine->status === 'privada' ? 'selected' : '' }}>Privada</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duration_weeks">Duración (Semanas) <span class="required">*</span></label>
                    <input type="number" id="duration_weeks" name="duration_weeks" min="1" max="52" 
                           value="{{ $routine->duration_weeks }}" required>
                </div>
                
                <div class="form-group">
                    <label for="days_per_week">Días por Semana <span class="required">*</span></label>
                    <input type="number" id="days_per_week" name="days_per_week" min="1" max="7" 
                           value="{{ $routine->days_per_week }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Descripción <span class="required">*</span></label>
                <textarea id="description" name="description" 
                          placeholder="Describe la rutina, objetivos específicos, recomendaciones..." required>{{ $routine->description }}</textarea>
            </div>
            
            <!-- Selector de Días -->
            <div class="form-group">
                <label>Días de Entrenamiento <span class="required">*</span></label>
                <p class="info-text">Selecciona {{ $routine->days_per_week }} día(s) de entrenamiento</p>
                <div class="days-selector" id="daysSelector">
                    @foreach ($days_of_week as $dayValue => $dayLabel)
                        <button type="button" class="day-button {{ in_array($dayValue, $trainingDays) ? 'selected' : '' }}" 
                                data-day="{{ $dayValue }}" data-day-label="{{ $dayLabel }}" onclick="toggleDay(this, event)">
                            {{ $dayLabel }}
                        </button>
                    @endforeach
                </div>
                <div id="training_days_container"></div>
                <p class="info-text" id="selectedDaysInfo"></p>
            </div>
            
            <!-- Sección de Ejercicios por Día -->
            <div class="exercises-by-day">
                <div class="section-title">🏋️ Ejercicios por Día</div>
                
                <div class="day-tabs" id="dayTabs">
                    <!-- Se llenarán dinámicamente con JavaScript -->
                </div>
                
                <div id="dayContents">
                    <!-- Se llenarán dinámicamente con JavaScript -->
                </div>
            
            <div class="button-group">
                <div class="button-row">
                    <button type="submit" class="submit-btn">💾 Actualizar Rutina</button>
                    <a href="{{ route('routines.index') }}" class="back-btn">← Volver</a>
                </div>
                
                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta rutina? No se podrá recuperar.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">🗑️ Eliminar Rutina</button>
                </form>
            </div>
        </form>
    </div>
    
    <script>
        const daysOfWeek = @json($days_of_week);
        let selectedDays = @json($trainingDays);
        
        function toggleDay(button, event) {
            event.preventDefault();
            const day = button.dataset.day;
            const daysPerWeek = parseInt(document.getElementById('days_per_week').value) || 1;
            
            if (button.classList.contains('selected')) {
                button.classList.remove('selected');
                selectedDays = selectedDays.filter(d => d !== day);
            } else {
                if (selectedDays.length >= daysPerWeek) {
                    alert(`Solo puedes seleccionar ${daysPerWeek} día(s)`);
                    return;
                }
                button.classList.add('selected');
                selectedDays.push(day);
            }
            
            updateDayTabs();
            updateTrainingDaysInput();
        }
        
        function updateDaySelector() {
            const daysPerWeek = parseInt(document.getElementById('days_per_week').value) || 1;
            document.getElementById('selectedDaysInfo').textContent = 
                `Debes seleccionar ${daysPerWeek} día(s). Seleccionados: ${selectedDays.length}/${daysPerWeek}`;
        }
        
        function updateDayTabs() {
            const dayTabs = document.getElementById('dayTabs');
            const dayContents = document.getElementById('dayContents');
            
            dayTabs.innerHTML = '';
            dayContents.innerHTML = '';
            
            selectedDays.forEach((day, index) => {
                const dayLabel = daysOfWeek[day];
                
                // Crear tab
                const tab = document.createElement('button');
                tab.type = 'button';
                tab.className = `day-tab ${index === 0 ? 'active' : ''}`;
                tab.textContent = dayLabel;
                tab.onclick = (e) => {
                    e.preventDefault();
                    switchDay(day, e.target);
                };
                dayTabs.appendChild(tab);
                
                // Crear contenedor de ejercicios para el día
                const content = document.createElement('div');
                content.className = `day-content ${index === 0 ? 'active' : ''}`;
                content.id = `day-${day}`;
                content.innerHTML = `
                    <div id="exercises-${day}"></div>
                    <button type="button" class="add-exercise-btn" onclick="addExerciseToDay('${day}', event)">
                        ➕ Agregar Ejercicio a ${dayLabel}
                    </button>
                `;
                dayContents.appendChild(content);
                
                // Agregar ejercicios existentes para este día
                const exercisesForDay = @json($selectedExercises).filter(ex => ex.day_name === day);
                if (exercisesForDay.length === 0) {
                    addExerciseToDay(day);
                } else {
                    exercisesForDay.forEach((ex, exIndex) => {
                        addExerciseToDay(day, null, ex);
                    });
                }
            });
            
            updateDaySelector();
        }
        
        function switchDay(day, tabElement) {
            document.querySelectorAll('.day-content').forEach(d => d.classList.remove('active'));
            document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
            document.getElementById(`day-${day}`).classList.add('active');
            if (tabElement) {
                tabElement.classList.add('active');
            }
        }
        
        function addExerciseToDay(day, event, existingEx = null) {
            if (event) event.preventDefault();
            
            const exercisesContainer = document.getElementById(`exercises-${day}`);
            const exerciseIndex = exercisesContainer.children.length;
            const uniqueId = existingEx ? `${day}-${existingEx.id}` : `${day}-${exerciseIndex}-${Date.now()}`;
            
            const exerciseItem = document.createElement('div');
            exerciseItem.className = 'exercise-item';
            exerciseItem.id = `exercise-${uniqueId}`;
            exerciseItem.innerHTML = `
                <div class="exercise-header">
                    <label style="margin: 0;">Ejercicio ${exerciseIndex + 1}</label>
                    <button type="button" class="remove-exercise" onclick="removeExercise('${uniqueId}', event)">
                        Remover
                    </button>
                </div>
                <div class="exercise-fields">
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Ejercicio</label>
                        <select name="exercises[]" class="exercise-select" required onchange="onExerciseSelectionChange(this, '${uniqueId}')">
                            <option value="" data-format="series_reps">-- Selecciona un ejercicio --</option>
                            @foreach ($exercises as $exercise)
                                <option value="{{ $exercise->id }}" data-format="{{ $exercise->exercise_format ?? 'series_reps' }}" ${existingEx && existingEx.exercise_id === {{ $exercise->id }} ? 'selected' : ''}>{{ $exercise->name }} ({{ $exercise->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group series-fields">
                        <label style="margin-bottom: 4px; font-size: 13px;">Series</label>
                        <input type="number" name="sets[]" placeholder="3" min="1" step="1"
                               value="${existingEx ? (existingEx.sets || '') : ''}">
                    </div>
                    <div class="form-group series-fields">
                        <label style="margin-bottom: 4px; font-size: 13px;">Repeticiones</label>
                        <input type="number" name="reps[]" placeholder="10" min="1" step="1"
                               value="${existingEx ? (existingEx.reps || '') : ''}">
                    </div>
                    <div class="form-group duration-fields" style="display: none;">
                        <label style="margin-bottom: 4px; font-size: 13px;">Duración</label>
                        <input type="number" name="durations[]" placeholder="30" min="1" step="1"
                               value="${existingEx ? (existingEx.duration || '') : ''}">
                    </div>
                    <div class="form-group duration-fields" style="display: none;">
                        <label style="margin-bottom: 4px; font-size: 13px;">Unidad</label>
                        <select name="duration_units[]">
                            <option value="segundos" ${existingEx && existingEx.duration_unit === 'segundos' ? 'selected' : ''}>Segundos</option>
                            <option value="minutos" ${existingEx && existingEx.duration_unit === 'minutos' ? 'selected' : ''}>Minutos</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Descanso</label>
                        <input type="number" name="descansos[]" placeholder="30" min="0" step="1"
                               value="${existingEx ? (existingEx.descansos || '') : ''}">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Unidad</label>
                        <select name="descansos_unidad[]">
                            <option value="segundos" ${existingEx && existingEx.descansos_unidad === 'segundos' ? 'selected' : ''}>Segundos</option>
                            <option value="minutos" ${existingEx && existingEx.descansos_unidad === 'minutos' ? 'selected' : ''}>Minutos</option>
                        </select>
                    </div>
                </div>
                
                <input type="hidden" name="exercise_days[]" value="${day}">
            `;
            
            exercisesContainer.appendChild(exerciseItem);
            updateExerciseFieldsByFormat(uniqueId);
            updateRemoveButtons();
        }
        
        function onExerciseSelectionChange(selectElement, uniqueId) {
            updateExerciseFieldsByFormat(uniqueId);
        }

        function getSelectedExerciseFormat(selectElement) {
            const option = selectElement.selectedOptions[0];
            return option?.dataset?.format || 'series_reps';
        }

        function updateExerciseFieldsByFormat(uniqueId) {
            const container = document.getElementById(`exercise-${uniqueId}`);
            if (!container) {
                return;
            }
            const select = container.querySelector('.exercise-select');
            if (!select) {
                return;
            }
            const format = getSelectedExerciseFormat(select);
            const seriesFields = container.querySelectorAll('.series-fields');
            const durationFields = container.querySelectorAll('.duration-fields');

            if (format === 'duration') {
                seriesFields.forEach(field => field.style.display = 'none');
                durationFields.forEach(field => field.style.display = 'block');
            } else {
                seriesFields.forEach(field => field.style.display = 'block');
                durationFields.forEach(field => field.style.display = 'none');
            }
        }
        
        function removeExercise(uniqueId, event) {
            event.preventDefault();
            document.getElementById(`exercise-${uniqueId}`).remove();
            updateRemoveButtons();
        }
        
        function updateRemoveButtons() {
            const items = document.querySelectorAll('.exercise-item');
            items.forEach((item, index) => {
                const button = item.querySelector('.remove-exercise');
                button.style.display = items.length > 1 ? 'block' : 'none';
            });
        }
        
        function updateTrainingDaysInput() {
            const container = document.getElementById('training_days_container');
            container.innerHTML = '';
            selectedDays.forEach(day => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'training_days[]';
                input.value = day;
                container.appendChild(input);
            });
        }
        
        function storeDayForExercise(uniqueId, day) {
            // El día ya está guardado en el hidden input
            const container = document.getElementById(`exercise-${uniqueId}`);
            if (!container) {
                return;
            }
            const select = container.querySelector('.exercise-select');
            if (select) {
                updateExerciseFieldsByFormat(uniqueId);
            }
        }
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateDayTabs();
            updateDaySelector();
        });
    </script>
</body>
</html>
