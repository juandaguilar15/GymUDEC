<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Rutina - GymUdec</title>
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
        
        /* Selector de días */
        .days-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .day-button {
            padding: 10px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
        }
        
        .day-button:hover {
            border-color: #1B5E46;
            background: #f0f8f5;
        }
        
        .day-button.selected {
            background: #1B5E46;
            color: white;
            border-color: #1B5E46;
        }
        
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Sección de Ejercicios por Día */
        .exercises-by-day {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #F8B803;
        }
        
        .day-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        
        .day-tab {
            padding: 8px 15px;
            background: white;
            border: none;
            border-radius: 3px 3px 0 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            color: #666;
            transition: all 0.3s;
        }
        
        .day-tab:hover {
            background: #f0f0f0;
        }
        
        .day-tab.active {
            background: #1B5E46;
            color: white;
        }
        
        .day-content {
            display: none;
        }
        
        .day-content.active {
            display: block;
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
        
        .exercise-fields {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 10px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
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
            font-size: 13px;
        }
        
        .add-exercise-btn:hover {
            background: #2a7a5e;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .section-title {
            color: #1B5E46;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .exercise-fields {
                grid-template-columns: 1fr;
            }
            
            .days-selector {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Crear Nueva Rutina</h1>
        
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('routines.store') }}" method="POST" id="routineForm">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nombre de la Rutina <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Ej: Rutina de Fuerza Principiante" 
                           value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="level">Nivel <span class="required">*</span></label>
                    <input type="text" id="level" name="level" placeholder="Ej: Principiante, Intermedio, Avanzado" 
                           value="{{ old('level') }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="objective">Objetivo <span class="required">*</span></label>
                    <select id="objective" name="objective" required>
                        <option value="">-- Selecciona un objetivo --</option>
                        <option value="fuerza" {{ old('objective') === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="cardio" {{ old('objective') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="mixto" {{ old('objective') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Estado <span class="required">*</span></label>
                    <select id="status" name="status" required>
                        <option value="publica" {{ old('status', 'publica') === 'publica' ? 'selected' : '' }}>Pública</option>
                        <option value="privada" {{ old('status') === 'privada' ? 'selected' : '' }}>Privada</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duration_weeks">Duración (Semanas) <span class="required">*</span></label>
                    <input type="number" id="duration_weeks" name="duration_weeks" min="1" max="52" 
                           value="{{ old('duration_weeks') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="days_per_week">Días por Semana <span class="required">*</span></label>
                    <input type="number" id="days_per_week" name="days_per_week" min="1" max="7" 
                           value="{{ old('days_per_week') }}" required onchange="updateDaySelector()">
                    <p class="info-text">Este número determina cuántos días seleccionar</p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Descripción <span class="required">*</span></label>
                <textarea id="description" name="description" 
                          placeholder="Describe la rutina, objetivos específicos, recomendaciones..." required>{{ old('description') }}</textarea>
            </div>
            
            <!-- Selector de Días -->
            <div class="form-group">
                <label>Días de Entrenamiento <span class="required">*</span></label>
                <p class="info-text">Selecciona {{ old('days_per_week', 1) }} día(s) de entrenamiento</p>
                <div class="days-selector" id="daysSelector">
                    @foreach ($days_of_week as $dayValue => $dayLabel)
                        <button type="button" class="day-button" data-day="{{ $dayValue }}" 
                                data-day-label="{{ $dayLabel }}" onclick="toggleDay(this, event)">
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
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit-btn">💾 Crear Rutina</button>
                <a href="{{ route('routines.index') }}" class="back-btn">← Volver</a>
            </div>
        </form>
    </div>
    
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
                    switchDay(day);
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
                
                // Agregar primer ejercicio automáticamente
                addExerciseToDay(day);
            });
            
            updateDaySelector();
        }
        
        function switchDay(day) {
            document.querySelectorAll('.day-content').forEach(d => d.classList.remove('active'));
            document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
            document.getElementById(`day-${day}`).classList.add('active');
            event.target.classList.add('active');
        }
        
        function addExerciseToDay(day, event) {
            if (event) event.preventDefault();
            
            const exercisesContainer = document.getElementById(`exercises-${day}`);
            const exerciseIndex = exercisesContainer.children.length;
            const uniqueId = `${day}-${exerciseIndex}-${Date.now()}`;
            
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
                        <select name="exercises[]" class="exercise-select" required onchange="storeDayForExercise('${uniqueId}', '${day}')">
                            <option value="">-- Selecciona un ejercicio --</option>
                            @foreach ($exercises as $exercise)
                                <option value="{{ $exercise->id }}">{{ $exercise->name }} ({{ $exercise->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Series</label>
                        <input type="number" name="sets[]" placeholder="3" min="1" step="1">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Repeticiones</label>
                        <input type="number" name="reps[]" placeholder="10" min="1" step="1">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Descanso</label>
                        <input type="number" name="descansos[]" placeholder="30" min="0" step="1">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 4px; font-size: 13px;">Unidad</label>
                        <select name="descansos_unidad[]">
                            <option value="segundos">Segundos</option>
                            <option value="minutos">Minutos</option>
                        </select>
                    </div>
                </div>
                
                <input type="hidden" name="exercise_days[]" value="${day}">
            `;
            
            exercisesContainer.appendChild(exerciseItem);
            updateRemoveButtons();
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
        }
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateDaySelector();
        });
    </script>
</body>
</html>
