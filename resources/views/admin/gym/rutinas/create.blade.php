<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Rutina - GymUdec</title>
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
        
        select,
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        select:focus,
        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 3px rgba(27, 94, 70, 0.1);
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
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Asignar Rutina a Estudiante</h1>
        
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('rutinas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="routine_id">Selecciona Rutina <span class="required">*</span></label>
                <select id="routine_id" name="routine_id" required>
                    <option value="">-- Selecciona una rutina --</option>
                    @foreach ($routines as $routine)
                        <option value="{{ $routine->id }}" {{ old('routine_id') == $routine->id ? 'selected' : '' }}>
                            {{ $routine->name }} ({{ ucfirst($routine->objective) }}) - {{ $routine->level }}
                        </option>
                    @endforeach
                </select>
                <p class="info-text">Selecciona la rutina que deseas asignar</p>
            </div>
            
            <div class="form-group">
                <label for="student_email">Selecciona Estudiante <span class="required">*</span></label>
                <select id="student_email" name="student_email" required>
                    <option value="">-- Selecciona un estudiante --</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->email }}" {{ old('student_email') == $student->email ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
                <p class="info-text">Selecciona el estudiante al que deseas asignar la rutina</p>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit-btn">✅ Asignar Rutina</button>
                <a href="{{ route('rutinas.index') }}" class="back-btn">← Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
