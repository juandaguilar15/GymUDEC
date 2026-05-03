<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Máquina - GymUdec</title>
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
        select {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="url"]:focus,
        select:focus {
            outline: none;
            border-color: #1B5E46;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #1B5E46;
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
        
        .image-preview {
            margin-top: 0.5rem;
            max-width: 200px;
        }
        
        .image-preview img {
            max-width: 100%;
            border-radius: 4px;
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
        <a href="{{ route('machines.index') }}" class="btn btn-secondary">← Volver al Listado</a>
    </nav>

    <div class="container">
        <h1>✏️ Editar Máquina</h1>
        
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
            <form method="POST" action="{{ route('machines.update', $machine->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nombre de la Máquina *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $machine->name) }}" placeholder="Ej: Trotadora, Press de Banca...">
                </div>
                
                <div class="form-group">
                    <label for="type">Tipo de Máquina *</label>
                    <select id="type" name="type" required>
                        <option value="">-- Selecciona un tipo --</option>
                        <option value="cardio" {{ old('type', $machine->type) === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="fuerza" {{ old('type', $machine->type) === 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="mixto" {{ old('type', $machine->type) === 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_url">URL de la Imagen</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $machine->image_url) }}" placeholder="Ej: https://example.com/maquina.jpg">
                    @if ($machine->image_url)
                        <div class="image-preview">
                            <img src="{{ $machine->image_url }}" alt="{{ $machine->name }}">
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Estado de la Máquina *</label>
                    <div class="checkbox-group">
                        <input type="checkbox" id="status" name="status" value="1" {{ old('status', $machine->status) ? 'checked' : '' }}>
                        <label for="status" style="margin-bottom: 0;">Máquina Activa</label>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-primary">💾 Actualizar Máquina</button>
                    <a href="{{ route('machines.index') }}" class="btn btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
