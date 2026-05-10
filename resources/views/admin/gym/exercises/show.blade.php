<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Ejercicio - GymUdec</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .exercise-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .exercise-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .exercise-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1B5E46;
        }

        .exercise-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #1B5E46;
            color: white;
        }

        .btn-primary:hover {
            background: #14523a;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .exercise-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .detail-section {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .detail-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .detail-item {
            margin-bottom: 0.75rem;
        }

        .detail-label {
            font-weight: 500;
            color: #6b7280;
            display: block;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #111827;
        }

        .exercise-media {
            margin-top: 2rem;
        }

        .media-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .media-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .media-item img,
        .media-item video {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .back-link:hover {
            color: #374151;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">🏋️</div>
            GymUdec
        </a>
    </nav>

    <div class="container">
        <a href="{{ route('exercises.index') }}" class="back-link">
            ← Volver al listado de ejercicios
        </a>

        <div class="exercise-card">
            <div class="exercise-header">
                <h1 class="exercise-title">{{ $exercise->name }}</h1>
                <div class="exercise-actions">
                    <a href="{{ route('exercises.edit', $exercise->id) }}" class="btn btn-primary">
                        ✏️ Editar
                    </a>
                    <form method="POST" action="{{ route('exercises.destroy', $exercise->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('¿Estás seguro de que deseas eliminar este ejercicio?')">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="exercise-details">
                <div class="detail-section">
                    <h2 class="detail-title">Información General</h2>
                    <div class="detail-item">
                        <span class="detail-label">Tipo:</span>
                        <span class="detail-value">{{ ucfirst($exercise->type) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Grupo Muscular:</span>
                        <span class="detail-value">{{ $exercise->muscle_group }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Formato:</span>
                        <span class="detail-value">
                            @if($exercise->exercise_format === 'series_reps')
                                Series y Repeticiones
                            @else
                                Por Duración (Tiempo)
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Máquina:</span>
                        <span class="detail-value">{{ $exercise->machine->name ?? 'No asignada' }}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h2 class="detail-title">Descripción</h2>
                    <p class="detail-value">{{ $exercise->description }}</p>
                </div>
            </div>

            @if($exercise->image_url || $exercise->media_url)
            <div class="exercise-media">
                <h2 class="detail-title">Multimedia</h2>
                <div class="media-grid">
                    @if($exercise->image_url)
                    <div class="media-item">
                        <h3>Imagen</h3>
                        @if(filter_var($exercise->image_url, FILTER_VALIDATE_URL))
                            <img src="{{ $exercise->image_url }}" alt="Imagen del ejercicio">
                        @else
                            <img src="{{ asset('storage/' . $exercise->image_url) }}" alt="Imagen del ejercicio">
                        @endif
                    </div>
                    @endif

                    @if($exercise->media_url)
                    <div class="media-item">
                        <h3>Video</h3>
                        @if(filter_var($exercise->media_url, FILTER_VALIDATE_URL))
                            <video controls>
                                <source src="{{ $exercise->media_url }}" type="video/mp4">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        @else
                            <video controls>
                                <source src="{{ asset('storage/' . $exercise->media_url) }}" type="video/mp4">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>