<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Rutinas - GymUdec</title>
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
        .container { max-width: 1000px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 16px; box-shadow: 0 16px 50px rgba(0,0,0,0.08); }
        h1 { color: #1B5E46; margin-bottom: 0.75rem; }
        .subtitle { color: #555; margin-bottom: 1.5rem; line-height: 1.6; }
        .notice { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; background: #e9f7ef; border: 1px solid #c8e6c9; color: #2e7d32; }
        .notice--warning { background: #fff4e5; border-color: #ffe0b2; color: #7a4b00; }
        .routine-grid { display: grid; gap: 1rem; }
        .routine-card { border: 1px solid #e0e0e0; border-radius: 14px; padding: 1.5rem; background: white; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .routine-card:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .routine-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
        .routine-title { font-size: 1.15rem; font-weight: 700; color: #1B5E46; margin-bottom: 0.5rem; }
        .badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.85rem; border-radius: 999px; background: #f1f8f6; color: #12713b; font-size: 0.8rem; font-weight: 700; }
        .badge--private { background: #fdecea; color: #991b1b; }
        .routine-meta { color: #555; font-size: 0.95rem; line-height: 1.6; }
        .routine-actions { margin-top: 1rem; }
        .routine-actions a { margin-right: 1rem; }
        .empty-state { text-align: center; padding: 3rem 2rem; border: 1px dashed #cfd8dc; border-radius: 16px; color: #546e7a; }
        .empty-state p { margin-bottom: 1rem; }
        .session-message { margin-bottom: 1rem; padding: 1rem 1.25rem; border-radius: 10px; }
        .session-success { background: #e8f5e9; border: 1px solid #c8e6c9; color: #256029; }
        .session-error { background: #fdecea; border: 1px solid #f5c6cb; color: #842029; }
        @media (max-width: 768px) { .navbar { flex-direction: column; align-items: stretch; } .navbar-actions { width: 100%; justify-content: space-between; } .routine-header { flex-direction: column; align-items: stretch; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            GymUdec
        </a>
        <div class="navbar-actions">
            @if($canCreate)
                <a href="{{ route('student.routines.create') }}" class="btn">Crear Rutina</a>
            @endif
            <a href="{{ route('logout') }}" class="btn-secondary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="session-message session-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="session-message session-error">{{ session('error') }}</div>
        @endif

        <h1>Mis Rutinas</h1>
        <p class="subtitle">
            @if($permisos === 'libre')
                Tienes permiso <strong>libre</strong>. Puedes crear tus propias rutinas y ver las que ya tienes asignadas.
            @else
                Tienes permiso <strong>limitado</strong>. Solo puedes ver la(s) rutina(s) asignada(s) por el administrador.
            @endif
        </p>

        @if($routines->isEmpty())
            <div class="empty-state">
                <p>No tienes rutinas registradas todavía.</p>
                @if($permisos === 'libre')
                    <a href="{{ route('student.routines.create') }}" class="btn">Crear mi primera rutina</a>
                @else
                    <p>Espera a que un administrador te asigne una rutina. Si tienes dudas, contacta con la enfermería o el administrador.</p>
                @endif
            </div>
        @else
            <div class="routine-grid">
                @foreach($routines as $routine)
                    <div class="routine-card">
                        <div class="routine-header">
                            <div>
                                <h2 class="routine-title">{{ $routine->name }}</h2>
                                <div class="routine-meta">Objetivo: {{ ucfirst($routine->objective) }} · Nivel: {{ ucfirst($routine->level) }}</div>
                            </div>
                            <span class="badge {{ $routine->status === 'privada' ? 'badge--private' : '' }}">{{ ucfirst($routine->status) }}</span>
                        </div>
                        <div class="routine-meta">Duración: {{ $routine->duration_weeks }} semana(s) · Días por semana: {{ $routine->days_per_week }}</div>
                        <div class="routine-meta" style="margin-top: 0.75rem;">{{ \Illuminate\Support\Str::limit($routine->description, 130) }}</div>
                        <div class="routine-actions">
                            <a href="{{ route('student.routines.show', $routine->id) }}" class="btn-secondary">Ver Detalles</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
