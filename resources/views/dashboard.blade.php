<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - GymUdec</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        
        .navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
            font-size: 1.5rem;
            color: white;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: #1B5E46;
            margin: 0;
        }
        
        .user-role {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
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
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: #E8A803;
            transform: translateY(-2px);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 5px solid #1B5E46;
        }
        
        .welcome-title {
            color: #1B5E46;
            margin: 0 0 1rem 0;
            font-size: 1.8rem;
        }
        
        .welcome-text {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        
        .user-details {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .detail-item {
            margin-bottom: 1.5rem;
        }
        
        .detail-label {
            color: #1B5E46;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        
        .detail-value {
            color: #333;
            font-size: 1.1rem;
            padding: 0.5rem;
            background: #f9f9f9;
            border-radius: 4px;
            border-left: 3px solid #F8B803;
            padding-left: 1rem;
        }
        
        .role-actions {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 5px solid #F8B803;
        }
        
        .role-actions h2 {
            color: #1B5E46;
            margin-top: 0;
        }
        
        .action-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #F8B803;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-right: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .action-btn:hover {
            background: #e6a700;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(248, 184, 3, 0.3);
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .navbar-user {
                width: 100%;
                justify-content: space-between;
            }
            
            .dashboard-container {
                padding: 0 1rem;
            }
            
            .welcome-card {
                padding: 1.5rem;
            }
            
            .user-details {
                padding: 1.5rem;
            }
            
            .action-btn {
                display: block;
                margin-bottom: 0.8rem;
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
        <div class="navbar-user">
            <div class="user-info">
                <p class="user-name">{{ auth()->user()->name }}</p>
                <p class="user-role">Rol: {{ ucfirst(auth()->user()->role) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        @if (session('success'))
            <div style="background: #e8f5e9; border: 1px solid #4caf50; color: #2e7d32; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="welcome-card">
            <h1 class="welcome-title">¡Bienvenido, {{ auth()->user()->name }}!</h1>
            <p class="welcome-text">
                @if(auth()->user()->role === 'enfermero')
                    Bienvenido al módulo de enfermería de GymUdec. Aquí podrás gestionar y 
                    registrar la información física de los estudiantes para optimizar su 
                    seguimiento y recomendaciones personalizadas.
                @else
                    Tu cuenta en GymUdec ha sido creada exitosamente. Aquí podrás gestionar tus rutinas, 
                    ver tu progreso y mantener un seguimiento inteligente de tu desempeño en el gimnasio.
                @endif
            </p>
        </div>

        <div class="user-details">
            <h2 style="color: #1B5E46; margin-top: 0;">Información de Tu Cuenta</h2>
            
            <div class="detail-item">
                <div class="detail-label">Nombre Completo:</div>
                <div class="detail-value">{{ auth()->user()->name }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Correo Electrónico:</div>
                <div class="detail-value">{{ auth()->user()->email }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Rol en el Sistema:</div>
                <div class="detail-value">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Miembro desde:</div>
                <div class="detail-value">{{ auth()->user()->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        
        @if(auth()->user()->role === 'enfermero')
            <div class="role-actions">
                <h2>👨‍⚕️ Gestión de Estudiantes</h2>
                <p style="color: #666; margin-bottom: 1.5rem;">
                    Accede a las herramientas de enfermería para gestionar la información 
                    física y médica de los estudiantes del gimnasio.
                </p>
                <a href="{{ route('nurse.search-student') }}" class="action-btn">
                    🔍 Buscar y Registrar Estudiante
                </a>
            </div>
        @elseif(auth()->user()->role === 'administrador')
            <div class="role-actions">
                <h2>⚙️ Panel Administrativo</h2>
                <p style="color: #666; margin-bottom: 1.5rem;">
                    Accede a las herramientas administrativas para gestionar el sistema.
                </p>
                <button class="action-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                    🔧 Próximamente
                </button>
            </div>
        @else
            <div class="role-actions">
                <h2>📱 Mi Perfil Estudiante</h2>
                <p style="color: #666; margin-bottom: 1.5rem;">
                    Aquí encontrarás tu información y tu progreso en el gimnasio.
                </p>
                <button class="action-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                    📊 Próximamente
                </button>
            </div>
        @endif
    </div>
</body>
</html>
