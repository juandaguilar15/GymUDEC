<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GymUdec - Gestión del Gimnasio Universitario</title>
    
    <!-- Fonts -->
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
            background: #f5f5f5;
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
        
        .navbar-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn-login, .btn-register {
            padding: 0.7rem 1.5rem;
            border: 2px solid #1B5E46;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-login {
            background: transparent;
            color: #1B5E46;
        }
        
        .btn-login:hover {
            background: #1B5E46;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-register {
            background: #1B5E46;
            color: white;
        }
        
        .btn-register:hover {
            background: #2a7a5e;
            border-color: #2a7a5e;
            transform: translateY(-2px);
        }
        
        .hero {
            min-height: calc(100vh - 80px);
            background: linear-gradient(135deg, rgba(27, 94, 70, 0.9) 0%, rgba(42, 122, 94, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        
        .hero-content {
            max-width: 700px;
        }
        
        .hero-badge {
            display: inline-block;
            background: rgba(248, 184, 3, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            border: 1px solid #F8B803;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 1rem 0;
        }
        
        .hero-title .highlight {
            color: #F8B803;
        }
        
        .hero-description {
            font-size: 1.2rem;
            line-height: 1.6;
            margin: 0 0 2rem 0;
            opacity: 0.95;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            padding: 0.9rem 2rem;
            background: #F8B803;
            color: #1B5E46;
            border: 2px solid #F8B803;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: white;
            color: #1B5E46;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            padding: 0.9rem 2rem;
            background: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #1B5E46;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .navbar-buttons {
                width: 100%;
                justify-content: flex-end;
                gap: 0.5rem;
            }
            
            .btn-login, .btn-register {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            
            .hero {
                min-height: auto;
                padding: 2rem 1rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-description {
                font-size: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec</span>
        </a>
        <div class="navbar-buttons">
            <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                🎓 Universidad de Cundinamarca
            </div>
            <h1 class="hero-title">
                Tu mejor versión comienza en el <span class="highlight">gimnasio</span>
            </h1>
            <p class="hero-description">
                Rutinas personalizadas, seguimiento inteligente y un avatar que refleja tu progreso. Exclusivo para estudiantes UdeC.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary">
                    Comenzar ahora
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="btn-secondary">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>
</body>
</html>
