<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymUdec - Enfermería')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen">
    <nav class="bg-white border-b border-emerald-100 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 no-underline">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white text-xl shadow-emerald-200 shadow-lg">⚕️</div>
                        <span class="text-emerald-900 text-xl font-bold tracking-tight">GymUdec <span class="text-emerald-500 font-medium">Salud</span></span>
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-emerald-900 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Módulo de Enfermería</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 px-4 py-2 rounded-md text-sm font-bold transition-all shadow-sm">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('nurse.search-student') }}" class="px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all {{ Request::routeIs('nurse.search-student') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-100' }}">
                <span>🔍</span> Buscar Estudiante
            </a>
            <a href="{{ route('nurse.list-students') }}" class="px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all {{ Request::routeIs('nurse.list-students') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-100' }}">
                <span>👥</span> Lista de Estudiantes
            </a>
            <a href="{{ route('analytics.index') }}" class="px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all {{ Request::routeIs('analytics.index') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-100' }}">
                <span>📊</span> Análisis y Datos
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-emerald-50 p-8 mb-8 border-l-8 border-l-emerald-600">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-emerald-950 mb-2">@yield('page-title')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-gray-500 text-lg">@yield('page-subtitle')</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3 items-center">
                    <a href="{{ route('dashboard') }}" class="btn-tertiary">🏠 Volver al Dashboard</a>
                    @hasSection('page-actions')
                        @yield('page-actions')
                    @endif
                </div>
            </div>
        </div>

        <div class="animate-fade-in">
            @yield('content')
        </div>
    </div>
</body>
</html>