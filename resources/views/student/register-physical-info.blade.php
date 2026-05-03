<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Información Física - GymUdec</title>
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
        }
        
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 100;
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
            font-size: 1.5rem;
        }
        
        .navbar-logout {
            display: flex;
            gap: 1rem;
        }
        
        .btn-logout {
            padding: 0.6rem 1.5rem;
            background: #F8B803;
            color: #1B5E46;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: #e6a700;
            transform: translateY(-2px);
        }
        
        .container {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: 80px;
        }
        
        .icon-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 1rem;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 28px;
            text-align: center;
            margin-bottom: 1rem;
            margin-top: 0;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            font-size: 16px;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .info-list {
            background: #f5f5f5;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #F8B803;
        }
        
        .info-list h3 {
            color: #1B5E46;
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-list ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .info-list li {
            color: #333;
            padding: 0.5rem 0;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-list li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }
        
        .action-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .action-section p {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .btn-primary {
            display: inline-block;
            padding: 1rem 2rem;
            background: #1B5E46;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            border: 2px solid #1B5E46;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        
        .btn-primary:hover {
            background: #2a7a5e;
            border-color: #2a7a5e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(27, 94, 70, 0.3);
        }
        
        .btn-secondary {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: white;
            color: #1B5E46;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border: 2px solid #1B5E46;
            cursor: pointer;
            margin-top: 1rem;
            width: 100%;
            box-sizing: border-box;
        }
        
        .btn-secondary:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
        }
        
        .status-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2rem;
        }
        
        .footer-note {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .container {
                padding: 1.5rem;
                margin-top: 120px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec</span>
        </a>
        <div class="navbar-logout">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="icon-container">
            <div class="icon">📋</div>
            <div class="status-badge">⚠️ Información Pendiente</div>
        </div>
        
        <h1>Bienvenido, {{ $user->name }}!</h1>
        
        <p class="subtitle">
            Para acceder a todas las funcionalidades del sistema GymUdec, 
            necesitas registrar tu información física en la enfermería.
        </p>
        
        <div class="info-list">
            <h3>📝 Datos que necesitarás registrar:</h3>
            <ul>
                <li>Edad</li>
                <li>Fecha de nacimiento</li>
                <li>Altura</li>
                <li>Peso</li>
                <li>Género</li>
                <li>Condición de salud</li>
                <li>Recomendaciones médicas</li>
            </ul>
        </div>
        
        <div class="action-section">
            <p>
                Dirígete a la <strong>enfermería del gimnasio</strong> para que 
                un profesional registre tu información física. Este proceso es 
                rápido y necesario para tu seguridad durante el entrenamiento.
            </p>
            
            <a href="{{ route('home') }}" class="btn-primary">
                ← Volver a Inicio
            </a>
            <button type="button" class="btn-secondary" onclick="openContact()">
                📞 Contactar Enfermería
            </button>
        </div>
        
        <div class="footer-note">
            Una vez registres tu información, podrás acceder al dashboard completo 
            y todas las funcionalidades del sistema.
        </div>
    </div>

    <script>
        function openContact() {
            alert('Por favor contacta con la enfermería del gimnasio GymUdec para registrar tu información física.\n\nCorreo: enfermeria@gymudec.com\nExt: 2024');
        }
    </script>
</body>
</html>
