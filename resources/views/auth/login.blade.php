<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - GymUdec</title>
    
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
            background: linear-gradient(135deg, rgba(27, 94, 70, 0.9) 0%, rgba(42, 122, 94, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1540497077202-7c8a3999166f?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .btn-back-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(27, 94, 70, 0.05);
            color: #1B5E46;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-back-home:hover {
            background: #1B5E46;
            color: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .login-title {
            font-size: 1.8rem;
            color: #1B5E46;
            font-weight: 700;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 35px;
            cursor: pointer;
            font-size: 1.2rem;
            color: #666;
            background: none;
            border: none;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #1B5E46;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            background: #fdfdfd;
        }
        
        input:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 4px rgba(27, 94, 70, 0.1);
            background: white;
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: #1B5E46;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            background: #2a7a5e;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(27, 94, 70, 0.3);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        
        .login-footer a {
            color: #1B5E46;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <a href="{{ route('home') }}" class="btn-back-home">← Volver a inicio</a>
        
        <div class="login-header">
            <div class="login-logo">💪</div>
            <h1 class="login-title">GymUdec</h1>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if (session('status'))
                <div style="background: #e6ffed; border: 1px solid #c6f6d5; color: #065f46; padding: 0.9rem; border-radius: 6px; margin-bottom: 1rem;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0.2rem 0;">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@ucundinamarca.edu.co" value="{{ old('email') }}" required>
                @error('email') <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="password-toggle" onclick="togglePasswordLogin()">👁️</button>
                @error('password') <span style="color: #d32f2f; font-size: 0.85rem; display:block; margin-top:0.4rem;">{{ $message }}</span> @enderror
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="{{ route('password.request') }}" style="color: #1B5E46; font-size: 0.85rem; text-decoration: none; font-weight: 500;">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>
            
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>
        
        <div class="login-footer">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Registrarse aquí</a>
        </div>
    </div>
</body>
</html>

<script>
function togglePasswordLogin() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = '👁️';
    }
}
</script>
