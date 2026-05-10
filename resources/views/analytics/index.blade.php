<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Datos - GymUdec</title>
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
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .page-shell {
            min-height: 100vh;
            background: #f5f5f5;
        }

        .topnav {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: #1B5E46;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.15rem;
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1B5E46;
        }

        .brand-subtitle {
            font-size: 0.95rem;
            color: #6b7b6c;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 0.85rem 1rem;
            border-radius: 999px;
            background: #f4f7fb;
            color: #2a7a5e;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: #eaf3ee;
        }

        .nav-link.active {
            background: #1B5E46;
            color: white;
        }

        .logout-form {
            margin: 0;
        }

        .logout-form .btn {
            min-width: auto;
            padding: 0.8rem 1rem;
        }

        .main-content {
            flex: 1;
            min-height: 100vh;
            padding: 2rem 0 3rem;
        }

        .hero {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1.5rem;
            align-items: center;
            background: linear-gradient(135deg, #1B5E46 0%, #2a7a5e 100%);
            border-radius: 18px;
            padding: 2rem 2.5rem;
            color: white;
            box-shadow: 0 18px 45px rgba(0,0,0,0.12);
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            margin: 0;
            font-size: 2rem;
            letter-spacing: -0.04em;
        }

        .hero p {
            margin: 0.75rem 0 0;
            color: rgba(255,255,255,0.9);
            max-width: 40rem;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .mobile-menu-toggle {
            display: none;
            background: #f4f7fb;
            border: 1px solid #d8e2da;
            color: #1B5E46;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-size: 1rem;
            cursor: pointer;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            font-size: 14px;
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        h1 {
            color: #1B5E46;
            font-size: 28px;
            margin-bottom: 1.5rem;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }

        input[type="date"] {
            padding: 0.7rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="date"]:focus {
            outline: none;
            border-color: #1B5E46;
        }

        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 8px;
            text-align: center;
            color: #999;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 1rem;
        }

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #F8B803;
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
        }

        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 1rem;
        }

        .chart-canvas {
            position: relative;
            height: 300px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #f5c6cb;
        }

        .export-btn {
            background: #1B5E46;
            color: white;
        }

        .export-btn:hover {
            background: #2a7a5e;
        }
        
        @media (max-width: 1024px) {
            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 14px 36px rgba(0,0,0,0.12);
                flex-direction: column;
                align-items: stretch;
                padding: 1rem 1.5rem;
                display: none;
            }

            .nav-menu.show {
                display: flex;
            }

            .main-content {
                padding: 1rem 0 2rem;
            }

            .mobile-menu-toggle {
                display: inline-flex;
            }

            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
            
            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .topnav {
                padding: 1rem 1rem;
            }
            
            .hero {
                grid-template-columns: 1fr;
                padding: 1.5rem 1.25rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .statistics-grid,
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="topnav">
            <div class="brand">
                <div class="brand-icon">💪</div>
                <div>
                    <div class="brand-title">GymUdec</div>
                    <div class="brand-subtitle">Panel de análisis y resultados</div>
                </div>
            </div>

            <button class="mobile-menu-toggle" onclick="toggleMenu()">☰</button>

            <nav class="nav-menu" id="topNavMenu">
                <a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a>
                <a href="{{ route('nurse.search-student') }}" class="nav-link">🔍 Buscar Estudiante</a>
                <a href="{{ route('nurse.list-students') }}" class="nav-link">👥 Lista de Estudiantes</a>
                <a href="{{ route('analytics.index') }}" class="nav-link active">📊 Estadísticas</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-secondary">🚪 Salir</button>
                </form>
            </nav>
        </header>

        <div class="main-content">
            <header class="topnav">
                <div class="brand">
                    <div class="brand-icon">💪</div>
                    <div>
                        <div class="brand-title">GymUdec</div>
                        <div class="brand-subtitle">Panel de análisis y resultados</div>
                    </div>
                </div>

                <button class="mobile-menu-toggle" onclick="toggleMenu()">☰</button>

                <nav class="nav-menu" id="topNavMenu">
                    <a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a>
                    <a href="{{ route('nurse.search-student') }}" class="nav-link">🔍 Buscar Estudiante</a>
                    <a href="{{ route('nurse.list-students') }}" class="nav-link">👥 Lista de Estudiantes</a>
                    <a href="{{ route('analytics.index') }}" class="nav-link active">📊 Estadísticas</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-secondary">🚪 Salir</button>
                    </form>
                </nav>
            </header>

            <div class="container">
                <div class="hero">
                    <div>
                        <h1>📊 Análisis de Información Física</h1>
                        <p>Resumen claro y moderno para entender mejor el estado físico de los estudiantes.</p>
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('analytics.export-csv', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn export-btn">⬇️ Descargar CSV</a>
                    </div>
                </div>

                @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif
        
        <!-- Filtro de fechas -->
        <div class="filter-section">
            <form method="GET" action="{{ route('analytics.index') }}" class="filter-form">
                <div class="form-group">
                    <label for="start_date">Fecha Inicial</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="end_date">Fecha Final</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">🔍 Filtrar Datos</button>
                </div>
            </form>
        </div>
        
        @if ($isEmpty)
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No hay registros en el rango de fechas seleccionado.</p>
            </div>
        @else
            <!-- Tarjetas de estadísticas principales -->
            <div class="statistics-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['count'] }}</div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['avgAge'] }}</div>
                    <div class="stat-label">Edad Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['avgWeight'] }} kg</div>
                    <div class="stat-label">Peso Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['avgHeight'] }} m</div>
                    <div class="stat-label">Altura Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['avgImc'] }}</div>
                    <div class="stat-label">IMC Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $statistics['minAge'] }}-{{ $statistics['maxAge'] }}</div>
                    <div class="stat-label">Rango de Edad</div>
                </div>
            </div>
            
            <!-- Gráficos principales -->
            <div class="grid-2">
                <!-- Distribución de género -->
                <div class="chart-container">
                    <div class="chart-title">👥 Distribución por Género</div>
                    <div class="chart-canvas">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
                
                <!-- Categorías de IMC -->
                <div class="chart-container">
                    <div class="chart-title">⚖️ Categorías de IMC</div>
                    <div class="chart-canvas">
                        <canvas id="imcChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos de promedios -->
            <div class="grid-3">
                <!-- Peso por género -->
                <div class="chart-container">
                    <div class="chart-title">⚖️ Peso Promedio por Género</div>
                    <div class="chart-canvas">
                        <canvas id="weightByGenderChart"></canvas>
                    </div>
                </div>
                
                <!-- Altura por género -->
                <div class="chart-container">
                    <div class="chart-title">📏 Altura Promedio por Género</div>
                    <div class="chart-canvas">
                        <canvas id="heightByGenderChart"></canvas>
                    </div>
                </div>
                
                <!-- Edad por género -->
                <div class="chart-container">
                    <div class="chart-title">🎂 Edad Promedio por Género</div>
                    <div class="chart-canvas">
                        <canvas id="ageByGenderChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Rango de edades -->
            <div class="chart-container">
                <div class="chart-title">📊 Distribución por Rango de Edad</div>
                <div class="chart-canvas">
                    <canvas id="ageRangeChart"></canvas>
                </div>
            </div>
        @endif
    </div>
</div>
</div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('topNavMenu');
            menu.classList.toggle('show');
        }

        window.addEventListener('resize', function() {
            const menu = document.getElementById('topNavMenu');
            if (window.innerWidth > 1024) {
                menu.classList.remove('show');
            }
        });
    </script>

    <script>
        const colors = {
            primary: '#1B5E46',
            secondary: '#2a7a5e',
            accent: '#F8B803',
            danger: '#e74c3c',
            success: '#27ae60',
            warning: '#f39c12',
            info: '#3498db',
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
        
        @if (!$isEmpty)
            // Gráfico de distribución por género
            const genderData = @json($statistics['genderDistribution']);
            const genderLabels = Object.keys(genderData).map(g => {
                const labels = {'masculino': 'Masculino', 'femenino': 'Femenino', 'otro': 'Otro'};
                return labels[g] || g;
            });
            const genderValues = Object.values(genderData);
            
            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: genderLabels,
                    datasets: [{
                        data: genderValues,
                        backgroundColor: [colors.primary, colors.secondary, colors.warning],
                        borderColor: 'white',
                        borderWidth: 2,
                    }],
                },
                options: chartOptions,
            });
            
            // Gráfico de categorías IMC
            const imcData = @json($statistics['imcCategories']);
            new Chart(document.getElementById('imcChart'), {
                type: 'bar',
                data: {
                    labels: ['Bajo Peso', 'Normal', 'Sobrepeso', 'Obesidad'],
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: [imcData.bajo_peso, imcData.normal, imcData.sobrepeso, imcData.obesidad],
                        backgroundColor: [colors.info, colors.success, colors.warning, colors.danger],
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
            
            // Peso por género
            const weightByGender = @json($statistics['weightByGender']);
            const weightLabels = Object.keys(weightByGender).map(g => {
                const labels = {'masculino': 'Masculino', 'femenino': 'Femenino', 'otro': 'Otro'};
                return labels[g] || g;
            });
            
            new Chart(document.getElementById('weightByGenderChart'), {
                type: 'bar',
                data: {
                    labels: weightLabels,
                    datasets: [{
                        label: 'Peso Promedio (kg)',
                        data: Object.values(weightByGender),
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
            
            // Altura por género
            const heightByGender = @json($statistics['heightByGender']);
            new Chart(document.getElementById('heightByGenderChart'), {
                type: 'bar',
                data: {
                    labels: weightLabels,
                    datasets: [{
                        label: 'Altura Promedio (m)',
                        data: Object.values(heightByGender),
                        backgroundColor: colors.secondary,
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
            
            // Edad por género
            const ageByGender = @json($statistics['ageByGender']);
            new Chart(document.getElementById('ageByGenderChart'), {
                type: 'bar',
                data: {
                    labels: weightLabels,
                    datasets: [{
                        label: 'Edad Promedio',
                        data: Object.values(ageByGender),
                        backgroundColor: colors.primary,
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
            
            // Rango de edades
            const ageRanges = @json($statistics['ageRanges']);
            new Chart(document.getElementById('ageRangeChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(ageRanges),
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: Object.values(ageRanges),
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
        @endif
    </script>
</body>
</html>
