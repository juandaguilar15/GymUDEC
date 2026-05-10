<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Navigation */
        .sidebar {
            width: 80px;
            background: linear-gradient(135deg, #1B5E46 0%, #2a7a5e 100%);
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 0;
            gap: 1rem;
            position: fixed;
            height: 100vh;
            z-index: 1000;
        }
        
        .sidebar-logo {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .sidebar-logo:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }
        
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }
        
        .sidebar-item {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            color: rgba(255,255,255,0.6);
            transition: all 0.3s;
            font-size: 1.5rem;
            position: relative;
            group: "nav";
        }
        
        .sidebar-item:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .sidebar-item.active {
            background: #F8B803;
            color: #1B5E46;
        }
        
        .sidebar-item:hover::after,
        .sidebar-item.active::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 70px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            z-index: 10;
        }
        
        .sidebar-logout {
            margin-top: auto;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            transition: all 0.3s;
            font-size: 1.5rem;
            cursor: pointer;
            border: none;
            background: none;
        }
        
        .sidebar-logout:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .main-content {
            margin-left: 80px;
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            width: calc(100% - 80px);
        }
        
        .navbar {
            background: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1B5E46;
            text-decoration: none;
        }
        
        .navbar-logo-icon {
            width: 36px;
            height: 36px;
            background: #F8B803;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1B5E46;
            font-size: 1.3rem;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-left: 1rem;
            border-left: 1px solid #e0e0e0;
        }
        
        .navbar-user-info {
            text-align: right;
        }
        
        .navbar-user-name {
            font-weight: 600;
            color: #1B5E46;
            font-size: 14px;
        }
        
        .navbar-user-role {
            font-size: 12px;
            color: #999;
        }
        
        .container {
            max-width: 100%;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 28px;
            margin-bottom: 1.5rem;
        }
        
        h2 {
            color: #1B5E46;
            font-size: 20px;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }
        
        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 3px solid #F8B803;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1B5E46;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-text {
            font-size: 12px;
            color: #bbb;
            margin-top: 0.5rem;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 1rem;
        }
        
        .chart-canvas {
            position: relative;
            height: 300px;
        }
        
        .recent-list {
            list-style: none;
        }
        
        .recent-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .recent-name {
            font-weight: 600;
            color: #1B5E46;
        }
        
        .recent-email {
            font-size: 12px;
            color: #999;
            margin-top: 0.2rem;
        }
        
        .recent-date {
            font-size: 12px;
            color: #999;
        }
        
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-estudiante {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-enfermero {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-administrador {
            background: #f8d7da;
            color: #721c24;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        @media (max-width: 1024px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .statistics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                bottom: 0;
                width: 100%;
                height: 70px;
                flex-direction: row;
                left: 0;
                right: 0;
                z-index: 1000;
            }
            
            .sidebar-logo {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                margin-bottom: 70px;
                width: 100%;
                padding: 1rem;
            }
            
            .navbar {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .navbar-user {
                border-left: none;
                border-top: 1px solid #e0e0e0;
                padding-left: 0;
                padding-top: 1rem;
                width: 100%;
            }
            
            .statistics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo" title="Ir al Dashboard">💪</a>
        
        <nav class="sidebar-nav">
            <a href="{{ route('admin.analytics') }}" class="sidebar-item" data-tooltip="Análisis">📊</a>
            <a href="{{ route('admin.users') }}" class="sidebar-item" data-tooltip="Usuarios">👥</a>
            <a href="{{ route('machines.index') }}" class="sidebar-item" data-tooltip="Gimnasio">🏋️</a>
            <a href="{{ route('routines.index') }}" class="sidebar-item" data-tooltip="Rutinas">📋</a>
            <a href="{{ route('rutinas.index') }}" class="sidebar-item" data-tooltip="Asignar">📤</a>
        </nav>
        
        <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%; display: flex; justify-content: center;">
            @csrf
            <button type="submit" class="sidebar-logout" title="Cerrar Sesión">🚪</button>
        </form>
    </aside>

    <div class="main-content">
        <!-- Navbar Top -->
        <nav class="navbar">
            <a href="{{ route('dashboard') }}" class="navbar-logo">
                <div class="navbar-logo-icon">💪</div>
                <span>GymUdec</span>
            </a>
            <div class="navbar-user">
                <div class="navbar-user-info">
                    <div class="navbar-user-name">{{ Auth::user()->name }}</div>
                    <div class="navbar-user-role">Administrador</div>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>⚙️ Panel de Administración</h1>
            
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif
            
            <!-- Tarjetas de estadísticas -->
            <div class="statistics-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $totalUsers }}</div>
                    <div class="stat-label">Total Usuarios</div>
                    <div class="info-text">Registrados en el sistema</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $totalEstudiantes }}</div>
                    <div class="stat-label">Estudiantes</div>
                    <div class="info-text">{{ round(($totalEstudiantes / $totalUsers * 100), 1) }}% del total</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalEnfermeros }}</div>
                <div class="stat-label">Enfermeros</div>
                <div class="info-text">Gestores de salud</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalAdmins }}</div>
                <div class="stat-label">Administradores</div>
                <div class="info-text">Gestores del sistema</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalPhysicalInfo }}</div>
                <div class="stat-label">Registros Físicos</div>
                <div class="info-text">Datos de estudiantes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $usersActiveToday }}</div>
                <div class="stat-label">Activos Hoy</div>
                <div class="info-text">Usuarios modificados</div>
            </div>
        </div>
        
        <!-- Sistema desde -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="stat-label">📅 Sistema Activo Desde</div>
            <div class="stat-value" style="font-size: 18px; margin-top: 0.5rem;">
                {{ $systemStartDate->format('d/m/Y H:i') }}
            </div>
            <div class="info-text">{{ $systemStartDate->diffForHumans() }}</div>
        </div>
        
        <!-- Nueva Sección: Gestión de Base de Datos -->
        <h2 style="margin-top: 2rem;">🛠️ Mantenimiento del Sistema</h2>
        <div class="card" style="margin-bottom: 2rem; border-left: 5px solid #1B5E46;">
            <div class="card-title">💾 Gestión de Base de Datos (Backup y Restauración)</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 1rem;">
                
                <!-- Exportar -->
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <div class="stat-label" style="margin-bottom: 0.5rem;">Copia de Seguridad</div>
                    <p class="info-text" style="margin-bottom: 1rem;">Descarga un archivo .sql con toda la información actual para prevenir pérdida de datos.</p>
                    <a href="{{ route('admin.database.export') }}" class="btn" style="background: #1B5E46; color: white; display: inline-flex; width: auto; padding: 0.8rem 1.5rem;">
                        <span>📥 Descargar Respaldo</span>
                    </a>
                </div>

                <!-- Importar -->
                <div style="padding: 1rem; background: #fff5f5; border-radius: 8px; border: 1px solid #feb2b2;">
                    <div class="stat-label" style="margin-bottom: 0.5rem; color: #c53030;">Restaurar Sistema</div>
                    <p class="info-text" style="margin-bottom: 1rem; color: #9b2c2c;"><strong>Atención:</strong> Subir un archivo reemplazará TODA la información actual del sistema.</p>
                    
                    <form action="{{ route('admin.database.import') }}" method="POST" enctype="multipart/form-data" id="restoreForm">
                        @csrf
                        <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                            <input type="file" name="backup_file" accept=".sql" required 
                                   style="font-size: 12px; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; background: white;">
                            <button type="submit" class="btn" 
                                    style="background: #e53e3e; color: white; padding: 0.8rem;"
                                    onclick="return confirm('¿ESTÁS ABSOLUTAMENTE SEGURO? Esta acción borrará los datos actuales y los reemplazará con los del archivo.')">
                                <span>📤 Restaurar Base de Datos</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid-2">
            <!-- Distribución de roles -->
            <div class="card">
                <div class="card-title">👥 Distribución de Roles</div>
                <div class="chart-canvas">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
            
            <!-- Registros por mes -->
            <div class="card">
                <div class="card-title">📈 Registros de Info Física por Mes</div>
                <div class="chart-canvas">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Últimos registros -->
        <div class="grid-2">
            <!-- Últimos usuarios -->
            <div class="card">
                <div class="card-title">👤 Últimos Usuarios Registrados</div>
                <ul class="recent-list">
                    @forelse ($recentUsers as $user)
                        <li class="recent-item">
                            <div>
                                <div class="recent-name">{{ $user->name }}</div>
                                <div class="recent-email">{{ $user->email }}</div>
                                <div class="recent-date">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </li>
                    @empty
                        <li class="recent-item">No hay usuarios registrados</li>
                    @endforelse
                </ul>
            </div>
            
            <!-- Últimos registros físicos -->
            <div class="card">
                <div class="card-title">📊 Últimos Registros Físicos</div>
                <ul class="recent-list">
                    @forelse ($recentPhysicalInfo as $info)
                        <li class="recent-item">
                            <div>
                                <div class="recent-name">{{ $info->user->name }}</div>
                                <div class="recent-email">{{ $info->email }}</div>
                                <div class="recent-date">{{ $info->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <span class="badge" style="background: #fff3cd; color: #856404;">{{ $info->age }} años</span>
                        </li>
                    @empty
                        <li class="recent-item">No hay registros físicos</li>
                    @endforelse
                </ul>
            </div>
        </div>
        </div>
    </div>

    <script>
        const colors = {
            primary: '#1B5E46',
            secondary: '#2a7a5e',
            accent: '#F8B803',
            success: '#27ae60',
            info: '#3498db',
            warning: '#f39c12',
        };
        
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {
                            family: "'Poppins', sans-serif",
                            size: 12,
                        },
                    },
                },
            },
        };
        
        // Gráfico de distribución de roles
        const roleData = @json($roleDistribution);
        new Chart(document.getElementById('roleChart'), {
            type: 'doughnut',
            data: {
                labels: ['Estudiantes', 'Enfermeros', 'Administradores'],
                datasets: [{
                    data: [roleData.estudiante, roleData.enfermero, roleData.administrador],
                    backgroundColor: [colors.success, colors.info, colors.primary],
                    borderColor: 'white',
                    borderWidth: 2,
                }],
            },
            options: chartOptions,
        });
        
        // Gráfico de registros por mes
        const monthlyData = @json($physicalInfoByMonth);
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(m => m.month),
                datasets: [{
                    label: 'Registros de Información Física',
                    data: monthlyData.map(m => m.count),
                    backgroundColor: colors.accent,
                }],
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
        
        // Marcar el item activo en la sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            
            sidebarItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && currentPath.includes(href.split('/').pop())) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
