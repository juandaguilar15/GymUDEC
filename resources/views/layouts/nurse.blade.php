<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymUdec - Enfermería')</title>
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
            background: #ecf3f2;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            min-height: 100vh;
            box-shadow: 2px 0 18px rgba(0,0,0,0.08);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, #1B5E46 0%, #2a7a5e 100%);
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .sidebar-logo-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .sidebar-user {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.4;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .nav-item {
            margin: 0.3rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.95rem 1.5rem;
            color: #2a7a5e;
            text-decoration: none;
            font-weight: 600;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #f3faf6;
            border-left-color: #F8B803;
            color: #1B5E46;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid #f0f0f0;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar.hidden ~ .main-content {
            margin-left: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background: #ecf3f2;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            background: white;
            padding: 1.25rem 2rem;
            box-shadow: 0 14px 40px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .topbar-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1B5E46;
        }

        .topbar-subtitle {
            font-size: 0.95rem;
            color: #6b7b6c;
        }

        .mobile-menu-toggle {
            display: none;
            background: rgba(255,255,255,0.2);
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            align-items: center;
            justify-content: center;
        }

        .page-body {
            flex: 1;
            padding: 2rem 2rem 3rem;
        }

        .page-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            padding: 2rem;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }

        .page-card h2 {
            margin: 0 0 1rem;
            font-size: 1.8rem;
            color: #1B5E46;
        }

        .page-card p {
            color: #5e7163;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        /* Buttons */
        .btn {
            padding: 0.85rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: transform 0.2s ease, background 0.2s ease;
            font-size: 0.95rem;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
        }

        .btn-primary {
            background: #F8B803;
            color: white;
        }

        .btn-primary:hover {
            background: #e6a700;
        }

        .btn-dark {
            background: #1B5E46;
            color: white;
        }

        .btn-dark:hover {
            background: #16543f;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
            padding: 0.4rem 0.8rem;
            font-size: 12px;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        input[type="email"],
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 14px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            background: #fafbfb;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1B5E46;
            box-shadow: 0 0 0 3px rgba(27,94,70,0.1);
            background: white;
        }

        .info-text {
            font-size: 0.85rem;
            color: #6b7b6c;
            margin-top: 8px;
        }

        /* Messages */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .error-message,
        .errors li {
            font-size: 0.95rem;
        }

        .errors {
            margin-bottom: 20px;
        }

        .errors ul {
            list-style: none;
            padding: 0;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1B5E46;
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        .email-cell {
            color: #666;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-link {
            padding: 0.4rem 0.8rem;
            background: #1B5E46;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            transition: background 0.3s;
        }

        .action-link:hover {
            background: #16543f;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar:not(.hidden) {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .topbar-title {
                font-size: 1.5rem;
            }

            .page-body {
                padding: 1rem;
            }

            .page-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">🏥</div>
                <span>GymUdec</span>
            </div>
            <div class="sidebar-user">
                {{ auth()->user()->name }}<br>
                <small>Enfermero</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('nurse.search-student') }}" class="nav-link {{ Request::routeIs('nurse.search-student') ? 'active' : '' }}">
                    🔍 Buscar Estudiante
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('nurse.list-students') }}" class="nav-link {{ Request::routeIs('nurse.list-students') ? 'active' : '' }}">
                    👥 Lista de Estudiantes
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('analytics.index') }}" class="nav-link">
                    📊 Análisis
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="btn btn-secondary" style="width: 100%;">🚪 Cerrar Sesión</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1 class="topbar-title">@yield('page-title')</h1>
                @hasSection('page-subtitle')
                    <p class="topbar-subtitle">@yield('page-subtitle')</p>
                @endif
            </div>
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>
        </div>

        <div class="page-body">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('hidden');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            if (!sidebar.contains(event.target) && !toggle.contains(event.target) && window.innerWidth <= 768) {
                sidebar.classList.add('hidden');
            }
        });
    </script>
</body>
</html>