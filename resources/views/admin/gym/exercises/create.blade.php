<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ejercicio - GymUdec</title>
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
        
        .navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1B5E46;
            text-decoration: none;
        }
        
        .navbar-logo-icon {
            width: 40px;
            height: 40px;
            background: #1B5E46;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 28px;
            margin-bottom: 2rem;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        label {
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="url"],
        select,
        textarea {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="url"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1B5E46;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-primary {
            background: #1B5E46;
            color: white;
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #2a7a5e;
        }
        
        .btn-cancel {
            flex: 1;
            padding: 0.8rem;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #f5c6cb;
        }
        
        .error-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .error-list li {
            padding: 0.3rem 0;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
            }
            
            .form-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec</span>
        </a>
        <a href="{{ route('exercises.index') }}" class="btn btn-secondary">← Volver al Listado</a>
    </nav>

    <div class="container">
        <h1>➕ Crear Nuevo Ejercicio</h1>
        
        @if ($errors->any())
            <div class="error-message">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="form-container">
            <form method="POST" action="{{ route('exercises.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nombre del Ejercicio *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="Ej: Press de Banca, Sentadilla...">
                </div>
                
                <div class="form-group">
                    <label for="type">Tipo de Ejercicio *</label>
                    <select id="type" name="type" required>
                        <option value="">-- Selecciona un tipo --</option>
                        <option value="cardio" {{ old('type') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="fuerza" {{ old('type') === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Descripción del Ejercicio *</label>
                    <textarea id="description" name="description" required placeholder="Describe el ejercicio, técnica, recomendaciones...">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="muscle_group">Grupo Muscular *</label>
                    <input type="text" id="muscle_group" name="muscle_group" required value="{{ old('muscle_group') }}" placeholder="Ej: Pecho, Espalda, Piernas...">
                </div>
                
                <div class="form-group">
                    <label for="machine_id">Máquina Asociada *</label>
                    <select id="machine_id" name="machine_id" required>
                        <option value="">-- Selecciona una máquina --</option>
                        @foreach ($machines as $machine)
                            <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{ $machine->name }} ({{ ucfirst($machine->type) }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_url">URL de la Imagen</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="Ej: https://example.com/ejercicio.jpg">
                </div>
                
                <div class="form-group">
                    <label for="media_url">URL del Contenido Multimedia (Video, Tutorial)</label>
                    <input type="url" id="media_url" name="media_url" value="{{ old('media_url') }}" placeholder="Ej: https://example.com/video.mp4">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-primary">💾 Guardar Ejercicio</button>
                    <a href="{{ route('exercises.index') }}" class="btn btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
