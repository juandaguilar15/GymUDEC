<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            background: linear-gradient(135deg, rgba(27, 94, 70, 0.9) 0%, rgba(42, 122, 94, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-position: center; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
            border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            padding: 3rem; width: 100%; max-width: 400px; text-align: center;
        }
        .title { color: #1B5E46; font-weight: 700; font-size: 1.5rem; margin-bottom: 1rem; }
        .desc { color: #666; font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.5; }
        .form-group { text-align: left; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        input {
            width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 8px;
            transition: all 0.3s ease; font-size: 1rem;
        }
        input:focus { outline: none; border-color: #1B5E46; box-shadow: 0 0 0 4px rgba(27, 94, 70, 0.1); }
        .btn-submit {
            width: 100%; padding: 1rem; background: #1B5E46; color: white; border: none;
            border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
        }
        .btn-submit:hover { background: #2a7a5e; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(27, 94, 70, 0.3); }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .back-link { margin-top: 1.5rem; display: block; color: #1B5E46; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Recuperar Acceso</h1>
        <p class="desc">Ingresa tu correo institucional y te enviaremos un enlace para restablecer tu contraseña.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@ucundinamarca.edu.co" required autofocus>
                @error('email') <p style="color: #c62828; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Enviar Enlace</button>
        </form>
        <a href="{{ route('login') }}" class="back-link">← Volver al inicio de sesión</a>
    </div>
</body>
</html>