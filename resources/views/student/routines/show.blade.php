<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Rutina - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; min-height: 100vh; }
        .navbar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .navbar-logo { display: flex; align-items: center; gap: 0.5rem; font-size: 1.4rem; font-weight: 700; color: #1B5E46; text-decoration: none; }
        .navbar-logo-icon { width: 40px; height: 40px; background: #1B5E46; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; }
        .navbar-actions { display: flex; gap: 0.75rem; align-items: center; }
        .btn, .btn-secondary { display: inline-block; padding: 0.75rem 1.3rem; border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
        .btn { background: #1B5E46; color: white; }
        .btn:hover { background: #155637; }
        .btn-secondary { background: white; color: #1B5E46; border: 2px solid #1B5E46; }
        .btn-secondary:hover { background: #f0f7f3; }
        .container { max-width: 900px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 16px; box-shadow: 0 16px 50px rgba(0,0,0,0.08); }
        .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .title { color: #1B5E46; margin: 0; font-size: 1.8rem; }
        .status-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.85rem; border-radius: 999px; background: #e8f5e9; color: #1b5e46; font-size: 0.85rem; font-weight: 700; }
        .meta { color: #555; font-size: 0.95rem; line-height: 1.7; margin-top: 0.75rem; }
        .section { margin-top: 2rem; }
        .section-title { font-size: 1.1rem; color: #1B5E46; margin-bottom: 1rem; }
        .day-card { border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.25rem; background: #fcfcfc; margin-bottom: 1rem; }
        .day-card h3 { margin: 0 0 0.75rem 0; font-size: 1rem; color: #1B5E46; }
        .exercise-item { border-top: 1px solid #e6e6e6; padding: 0.75rem 0; display: grid; grid-template-columns: 1fr auto; gap: 1rem; }
        .exercise-item:first-child { border-top: none; }
        .exercise-main { color: #333; }
        .exercise-meta { color: #555; font-size: 0.9rem; line-height: 1.6; }
        .exercise-label { font-weight: 600; margin-bottom: 0.35rem; }
        .section-note { margin-top: 1rem; color: #666; font-size: 0.95rem; }
        @media (max-width: 768px) { .header { flex-direction: column; } .exercise-item { grid-template-columns: 1fr; } .navbar { flex-direction: column; } .navbar-actions { width: 100%; justify-content: space-between; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            GymUdec
        </a>
        <div class="navbar-actions">
            <a href="{{ route('student.routines.index') }}" class="btn-secondary">← Volver</a>
            <a href="{{ route('logout') }}" class="btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <div>
                <h1 class="title">{{ $routine->name }}</h1>
                <p class="meta">Objetivo: {{ ucfirst($routine->objective) }} · Nivel: {{ ucfirst($routine->level) }} · Duración: {{ $routine->duration_weeks }} semana(s)</p>
                <p class="meta">Días por semana: {{ $routine->days_per_week }}</p>
                <p class="meta">{{ $routine->description }}</p>
            </div>
            <span class="status-badge">{{ ucfirst($routine->status) }}</span>
        </div>

        <div class="section">
            <h2 class="section-title">Días de entrenamiento</h2>
            @foreach($routine->trainingDays as $trainingDay)
                <div class="day-card">
                    <h3>{{ ucfirst($trainingDay->day_name) }}</h3>
                    @if($trainingDay->exercises->isEmpty())
                        <p class="exercise-meta">No hay ejercicios asignados para este día.</p>
                    @else
                        @foreach($trainingDay->exercises as $exerciseItem)
                            <div class="exercise-item">
                                <div class="exercise-main">
                                    <div class="exercise-label">{{ $exerciseItem->exercise?->name ?? 'Ejercicio no disponible' }}</div>
                                    <div class="exercise-meta">{{ $exerciseItem->exercise?->type ? ucfirst($exerciseItem->exercise->type) : 'Tipo desconocido' }}</div>
                                </div>
                                <div class="exercise-meta">
                                    @if($exerciseItem->exercise?->exercise_format === 'duration')
                                        <p>Duración: {{ $exerciseItem->duration ?? 'N/A' }} {{ $exerciseItem->duration_unit ?? '' }}</p>
                                    @else
                                        <p>Series: {{ $exerciseItem->sets ?? 'N/A' }}</p>
                                        <p>Reps: {{ $exerciseItem->reps ?? 'N/A' }}</p>
                                    @endif
                                    <p>Descanso: {{ $exerciseItem->rests ?? 'N/A' }} {{ $exerciseItem->rests_unit ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
            <p class="section-note">Recuerda seguir las recomendaciones de la enfermería y consultar al administrador en caso de dudas.</p>
        </div>
    </div>
</body>
</html>
