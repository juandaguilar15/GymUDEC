<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Médico - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { margin: 0; background: #f5faff; }
        .navbar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e3f2fd; }
        .btn-logout { padding: 0.6rem 1.5rem; background: #F8B803; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .medical-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-left: 6px solid #2196f3; }
        .role-badge { background: #e3f2fd; color: #2196f3; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="font-weight: 700; font-size: 1.4rem; color: #1B5E46;">GymUdec <span style="color: #2196f3;">Salud</span></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-logout">Cerrar Sesión</button></form>
    </nav>
    <div class="container">
        <div class="medical-card">
            <span class="role-badge">ENFERMERÍA / MÉDICO</span>
            <h1 style="color: #2196f3;">Evaluación Médica</h1>
            <p>Bienvenido, <strong>{{ auth()->user()->name }}</strong>. Inicia las valoraciones físicas de los estudiantes.</p>
            <div style="margin-top: 2rem; padding: 2rem; background: #f1f8ff; border-radius: 8px; border: 1px dashed #2196f3;">
                <h3>Estudiantes pendientes por valoración:</h3>
                <ul>
                    <li>Buscar estudiante por código...</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>