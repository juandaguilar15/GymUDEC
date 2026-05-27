<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymUdec - Administración')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen">
    <nav class="bg-white border-b border-emerald-100 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 py-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-600 shadow-emerald-200 shadow-lg grid place-items-center text-white text-xl font-semibold">AD</div>
                    <div>
                        <p class="text-base font-bold text-emerald-950 leading-none">GymUdec Administración</p>
                        <p class="text-sm text-slate-500">Gestiona usuarios, máquinas, rutinas y avisos del sistema.</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 justify-end">
                    <a href="{{ route('dashboard') }}" class="btn-tertiary">🏠 Volver al Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn-secondary">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="admin-header">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-emerald-950 mb-2">@yield('page-title')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-slate-600 text-lg">@yield('page-subtitle')</p>
                    @endif
                </div>
                <div class="admin-top-actions">
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
    @stack('scripts')
</body>
</html>
