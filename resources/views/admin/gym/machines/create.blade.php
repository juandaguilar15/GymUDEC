<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Máquina - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #1B5E46; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #444; }
        input, select, textarea { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; }
        .btn { background: #1B5E46; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        .btn-back { background: #6c757d; margin-top: 1rem; text-decoration: none; display: block; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏋️ Nueva Máquina</h1>

        <form action="{{ route('machines.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nombre de la Máquina</label>
                <input type="text" name="name" required placeholder="Ej: Prensa de Pierna">
            </div>

            <div class="form-group">
                <label>Tipo</label>
                <select name="type" required>
                    <option value="cardio">Cardio</option>
                    <option value="fuerza">Fuerza</option>
                    <option value="mixto">Mixto</option>
                </select>
            </div>

            <div class="form-group">
                <label>Imagen de la Máquina</label>
                <input type="file" name="image_url" accept="image/*">
                <small style="color: #888;">Formatos: JPG, PNG. Máx: 2MB</small>
            </div>

            <div class="form-group">
                <label>Estado Inicial</label>
                <select name="status" required>
                    <option value="1">Disponible</option>
                    <option value="0">En Mantenimiento</option>
                </select>
            </div>

            <button type="submit" class="btn">Guardar Máquina</button>
            <a href="{{ route('machines.index') }}" class="btn btn-back">Volver al listado</a>
        </form>
    </div>
</body>
</html>