<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Médico - GymUdec</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { margin: 0; background: #f5faff; }
        .navbar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e3f2fd; }
        .btn-logout { padding: 0.6rem 1.5rem; background: #F8B803; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .medical-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-left: 6px solid #2196f3; }
        .role-badge { background: #e3f2fd; color: #2196f3; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f5f5f5; min-height: 100vh; }
        
        .navbar {
            background: white; padding: 1rem 2rem; display: flex; justify-content: space-between;
            align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 2rem;
        }
        .navbar-logo { display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem; font-weight: 700; color: #1B5E46; text-decoration: none; }
        .navbar-logo-icon { width: 40px; height: 40px; background: #1B5E46; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; }
        .user-meta { text-align: right; margin-right: 1.5rem; }
        .user-name { font-weight: 600; color: #1B5E46; font-size: 0.9rem; }
        .user-role { font-size: 0.75rem; color: #666; text-transform: uppercase; }

        .container { max-width: 1400px; margin: 0 auto; padding: 0 2rem 3rem; }
        h1 { color: #1B5E46; font-size: 24px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 5px solid #F8B803; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-value { font-size: 28px; font-weight: 700; color: #1B5E46; }
        .stat-label { font-size: 11px; color: #999; text-transform: uppercase; font-weight: 600; margin-top: 5px; }

        /* Charts Section */
        .charts-section { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 2.5rem; }
        .chart-container { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .chart-title { font-size: 14px; font-weight: 600; color: #1B5E46; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .canvas-wrapper { position: relative; height: 250px; }

        /* User Management */
        .section-card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
        .section-header { padding: 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .search-form { display: flex; gap: 0.5rem; flex-grow: 1; max-width: 400px; }
        .search-input { flex-grow: 1; padding: 0.6rem 1rem; border: 2px solid #e0e0e0; border-radius: 4px; font-size: 14px; }
        .search-input:focus { outline: none; border-color: #1B5E46; }

        /* Table Styles */
        table { width: 100%; border-collapse: collapse; }
        thead { background: #1B5E46; color: white; }
        th { padding: 1rem; text-align: left; font-size: 13px; font-weight: 500; }
        td { padding: 1rem; border-bottom: 1px solid #f0f0f0; font-size: 14px; color: #444; }
        tbody tr:hover { background: #f9fdfb; }
        .role-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .role-estudiante { background: #e3f2fd; color: #1976d2; }
        .role-enfermero { background: #f1f8e9; color: #388e3c; }
        .role-administrador { background: #fff3e0; color: #f57c00; }

        /* Buttons */
        .btn { padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 12px; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; }
        .btn-primary { background: #F8B803; color: white; }
        .btn-primary:hover { background: #e6a700; }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-sm { padding: 0.3rem 0.6rem; }
        .btn-logout { background: #F8B803; color: #1B5E46; font-size: 14px; padding: 0.6rem 1.2rem; }

        /* Pagination */
        .pagination-wrapper { padding: 1.5rem; display: flex; justify-content: center; }

        @media (max-width: 1024px) {
            .charts-section { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 1rem; text-align: center; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .section-header { flex-direction: column; align-items: stretch; }
            td:nth-child(4), th:nth-child(4) { display: none; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="font-weight: 700; font-size: 1.4rem; color: #1B5E46;">GymUdec <span style="color: #2196f3;">Salud</span></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-logout">Cerrar Sesión</button></form>
        <a href="{{ route('admin.dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec Admin</span>
        </a>
        <div style="display: flex; align-items: center;">
            <div class="user-meta">
                <p class="user-name">{{ auth()->user()->name }}</p>
                <p class="user-role">Control Total</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="medical-card">
            <span class="role-badge">ENFERMERÍA / MÉDICO</span>
            <h1 style="color: #2196f3;">Evaluación Médica</h1>
            <p>Bienvenido, <strong>{{ auth()->user()->name }}</strong>. Inicia las valoraciones físicas de los estudiantes.</p>
            <div style="margin-top: 2rem; padding: 2rem; background: #f1f8ff; border-radius: 8px; border: 1px dashed #2196f3;">
                <h3>Estudiantes pendientes por valoración:</h3>
                <ul>
                    <li>Buscar estudiante por código...</li>
                </ul>
        <h1>⚙️ Panel de Control Administrativo</h1>

        <!-- 1. PANEL PRINCIPAL (Stats) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['totalUsers'] }}</div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card" style="border-left-color: #2196f3;">
                <div class="stat-value">{{ $stats['totalNurses'] }}</div>
                <div class="stat-label">Personal de Enfermería</div>
            </div>
            <div class="stat-card" style="border-left-color: #4caf50;">
                <div class="stat-value">{{ $stats['totalStudents'] }}</div>
                <div class="stat-label">Estudiantes Activos</div>
            </div>
            <div class="stat-card" style="border-left-color: #1B5E46;">
                <div class="stat-value">{{ $stats['totalPhysicalInfos'] }}</div>
                <div class="stat-label">Fichas Médicas</div>
            </div>
            <div class="stat-card" style="border-left-color: #e91e63;">
                <div class="stat-value">{{ $stats['activeToday'] }}</div>
                <div class="stat-label">Sesiones Hoy</div>
            </div>
            <div class="stat-card" style="border-left-color: #607d8b;">
                <div class="stat-value" style="font-size: 18px; margin-top: 10px;">{{ $stats['systemSince'] }}</div>
                <div class="stat-label">En Línea Desde</div>
            </div>
        </div>

        <!-- 3. SECCIÓN DE ESTADÍSTICAS (Gráficos) -->
        <div class="charts-section">
            <div class="chart-container">
                <div class="chart-title">👥 Distribución de Roles</div>
                <div class="canvas-wrapper">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">📈 Registros de Información Física (Mensual)</div>
                <div class="canvas-wrapper">
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. SECCIÓN DE GESTIÓN DE USUARIOS -->
        <div class="section-card">
            <div class="section-header">
                <h2 style="font-size: 18px; color: #1B5E46;">👥 Gestión de Usuarios</h2>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Buscar por nombre o email..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary">🔍</button>
                </form>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td style="color: #666; font-size: 13px;">{{ $u->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $u->role }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td>{{ $u->created_at->format('d/m/Y') }}</td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 5px; justify-content: flex-end;">
                                    <button class="btn btn-secondary btn-sm" title="Ver Detalles">👁️</button>
                                    <button class="btn btn-primary btn-sm" title="Cambiar Rol">🔄</button>
                                    @if($u->id !== auth()->id())
                                        <form action="#" method="POST" onsubmit="return confirm('¿Eliminar usuario?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->appends(['search' => $search ?? ''])->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        const palette = {
            primary: '#1B5E46',
            secondary: '#2a7a5e',
            accent: '#F8B803',
            light: '#e8f5e9'
        };

        // 1. Gráfico de Roles (Pie)
        const roleCtx = document.getElementById('roleChart').getContext('2d');
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Estudiantes', 'Enfermeros', 'Admins'],
                datasets: [{
                    data: [
                        {{ $roleDistribution['estudiante'] ?? 0 }}, 
                        {{ $roleDistribution['enfermero'] ?? 0 }}, 
                        {{ $roleDistribution['administrador'] ?? 0 }}
                    ],
                    backgroundColor: [palette.primary, palette.accent, '#333'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 } } }
                }
            }
        });

        // 2. Gráfico Mensual (Bar)
        const historyCtx = document.getElementById('historyChart').getContext('2d');
        
        // Preparar meses
        const monthNames = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        const labels = [];
        const values = [];
        
        @php
            if(isset($recordsByMonth)) {
                for($i = 5; $i >= 0; $i--) {
                    $m = now()->subMonths($i)->month;
                    echo "labels.push(monthNames[" . ($m-1) . "]);";
                    echo "values.push(" . ($recordsByMonth[$m] ?? 0) . ");";
                }
            }
        @endphp

        new Chart(historyCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros Físicos',
                    data: values,
                    backgroundColor: palette.secondary,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>