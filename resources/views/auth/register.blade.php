<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse - GymUdec</title>
    
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
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
        }
        
        .btn-back-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(27, 94, 70, 0.1);
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
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .register-title {
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
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        input:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 3px rgba(27, 94, 70, 0.1);
        }
        
        .form-note {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.3rem;
        }
        
        .btn-register {
            width: 100%;
            padding: 0.9rem;
            background: #1B5E46;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: #2a7a5e;
            transform: translateY(-2px);
        }
        
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        
        .register-footer a {
            color: #1B5E46;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .register-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <a href="{{ route('home') }}" class="btn-back-home">← Volver a inicio</a>
        
        <div class="register-header">
            <div class="register-logo">💪</div>
            <h1 class="register-title">GymUdec</h1>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0.3rem 0;">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <input type="text" id="name" name="name" placeholder="Juan Pérez" value="{{ old('name') }}" required>
                @error('name') <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@ucundinamarca.edu.co" value="{{ old('email') }}" required>
                <div class="form-note">Usa tu correo institucional @ucundinamarca.edu.co</div>
                @error('email') <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="password-toggle" onclick="togglePasswordRegister('password')">👁️</button>
                @error('password') <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                <button type="button" class="password-toggle" onclick="togglePasswordRegister('password_confirmation')">👁️</button>
            </div>
            
            <button type="submit" class="btn-register">Registrarse</button>
        </form>
        
        <div class="register-footer">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>

<script>
function togglePasswordRegister(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleBtn = event.target;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = '👁️';
    }
}
</script>
