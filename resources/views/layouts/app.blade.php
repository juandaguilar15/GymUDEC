<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'GymUdec')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; margin:0; background:#f5f5f5; }
        .navbar { background:white; padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 4px rgba(0,0,0,0.05); }
        .navbar-logo { display:flex; align-items:center; gap:0.5rem; font-size:1.2rem; font-weight:700; color:#1B5E46; text-decoration:none; }
        .navbar-logo-icon { width:36px; height:36px; background:#1B5E46; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; }
        .navbar-user { display:flex; align-items:center; gap:1rem; }
        .container { max-width:1100px; margin:2rem auto; padding:0 1rem; }
    </style>
    @stack('head')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec</span>
        </a>
        <div class="navbar-user">
            @auth
                @include('partials.notifications-dropdown')
                <div style="text-align:right;">
                    <div style="font-weight:600;color:#1B5E46;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.85rem;color:#666;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="background:#F8B803;color:#1B5E46;border:none;padding:0.5rem 0.9rem;border-radius:4px;cursor:pointer;">Cerrar Sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="text-decoration:none;color:#1B5E46;margin-right:0.5rem;">Ingresar</a>
                <a href="{{ route('register') }}" style="background:#1B5E46;color:#fff;padding:0.4rem 0.8rem;border-radius:4px;text-decoration:none;">Registro</a>
            @endauth
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
