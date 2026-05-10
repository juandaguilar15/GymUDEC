<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ejercicio - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; padding: 2rem; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #1B5E46; margin-bottom: 1.5rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1.2rem; }
        .full-width { grid-column: span 2; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #444; }
        input, select, textarea { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; }
        .btn { background: #1B5E46; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        .error-message { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; border-left: 4px solid #f5c6cb; }
        .error-list { margin: 0; padding-left: 1.2rem; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #1B5E46; box-shadow: 0 0 0 2px rgba(27, 94, 70, 0.1); }
        small { display: block; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💪 Nuevo Ejercicio</h1>

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

        <form action="{{ route('exercises.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nombre del Ejercicio</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Ej: Sentadilla con barra">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="type" required>
                        <option value="fuerza" {{ old('type') == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                        <option value="cardio" {{ old('type') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Máquina Asociada</label>
                    <select name="machine_id" required>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{ $machine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Formato de Ejecución</label>
                    <select name="exercise_format" required>
                        <option value="series_reps" {{ old('exercise_format') == 'series_reps' ? 'selected' : '' }}>Series y Repeticiones</option>
                        <option value="duration" {{ old('exercise_format') == 'duration' ? 'selected' : '' }}>Por Duración (Tiempo)</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label>Grupo Muscular</label>
                    <input type="text" name="muscle_group" required value="{{ old('muscle_group') }}" placeholder="Ej: Cuádriceps, Glúteos">
                </div>

                <div class="form-group full-width">
                    <label>Descripción / Instrucciones</label>
                    <textarea name="description" rows="3" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Imagen (Poster/Miniatura)</label>
                    <input type="file" name="image_url" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Video Demostrativo</label>
                    <input type="file" name="media_url" accept="video/*">
                    <small style="color: #888;">Formatos: MP4, MOV. Máx: 20MB</small>
                </div>
            </div>

            <button type="submit" class="btn" style="margin-top: 1rem;">Crear Ejercicio</button>
            <a href="{{ route('exercises.index') }}" style="display: block; text-align: center; margin-top: 1rem; color: #666; text-decoration: none;">Cancelar</a>
        </form>
    </div>
</body>
</html>