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
        
        .navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1B5E46;
            text-decoration: none;
        }
        
        .navbar-logo-icon {
            width: 40px;
            height: 40px;
            background: #1B5E46;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .navbar-actions {
            display: flex;
            gap: 1rem;
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
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
            
            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .statistics-grid {
                grid-template-columns: 1fr;
            }
            
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">💪</div>
            <span>GymUdec</span>
        </a>
        <div class="navbar-actions">
            <a href="{{ route('nurse.search-student') }}" class="btn btn-secondary">← Volver</a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary">Salir</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>📊 Análisis de Información Física</h1>
        
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
                <div class="form-group">
                    <form action="{{ route('analytics.export-csv') }}" method="GET" style="margin: 0;">
                        <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                        <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                        <button type="submit" class="btn export-btn">⬇️ Descargar CSV</button>
                    </form>
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
