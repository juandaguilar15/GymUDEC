<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Física - GymUdec</title>
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
            background: linear-gradient(135deg, #1B5E46 0%, #2a7a5e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        .student-info {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #F8B803;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .student-info h3 {
            color: #1B5E46;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .student-info p {
            color: #333;
            font-size: 16px;
            margin: 3px 0;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 24px;
            margin-bottom: 25px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            margin-bottom: 0;
        }
        
        label {
            display: block;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="email"],
        input[type="text"],
        input[type="date"],
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
        
        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 3px rgba(27, 94, 70, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
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
        
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .required {
            color: #e74c3c;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Información Física del Estudiante</h1>
        
        <div class="student-info">
            <h3>Estudiante</h3>
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
            
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Edad <span class="required">*</span></label>
                    <input type="number" id="age" name="age" min="15" max="100" 
                           value="{{ $physicalInfo->age ?? old('age') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Género <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino" {{ ($physicalInfo->gender ?? old('gender')) === 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ ($physicalInfo->gender ?? old('gender')) === 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ ($physicalInfo->gender ?? old('gender')) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Fecha de Nacimiento <span class="required">*</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth" 
                       value="{{ $physicalInfo->date_of_birth ?? old('date_of_birth') }}" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="height">Altura (metros) <span class="required">*</span></label>
                    <input type="number" id="height" name="height" step="0.01" min="1" max="3"
                           placeholder="1.75" value="{{ $physicalInfo->height ?? old('height') }}" required>
                    <p class="info-text">Ejemplo: 1.75</p>
                </div>
                
                <div class="form-group">
                    <label for="weight">Peso (kg) <span class="required">*</span></label>
                    <input type="number" id="weight" name="weight" step="0.1" min="20" max="300"
                           placeholder="75.5" value="{{ $physicalInfo->weight ?? old('weight') }}" required>
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
                <label for="permisos">Permisos para Crear Rutinas <span class="required">*</span></label>
                <select id="permisos" name="permisos" required>
                    <option value="">Selecciona una opción</option>
                    <option value="libre" {{ ($physicalInfo->permisos ?? old('permisos')) === 'libre' ? 'selected' : '' }}>Libre (El estudiante puede crear sus propias rutinas)</option>
                    <option value="limitado" {{ ($physicalInfo->permisos ?? old('permisos')) === 'limitado' ? 'selected' : '' }}>Limitado (Solo el admin puede asignar rutinas)</option>
                </select>
                <p class="info-text">Selecciona según el estado físico y experiencia del estudiante en el gimnasio</p>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit-btn">💾 Guardar Información</button>
                <a href="{{ route('nurse.search-student') }}" class="back-btn">← Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
