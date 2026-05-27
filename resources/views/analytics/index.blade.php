@extends('layouts.nurse')

@section('title', 'Análisis de Datos - GymUdec')

@section('page-title', '📊 Análisis de Información Física')

@section('page-subtitle', 'Resumen claro y moderno para entender mejor el estado físico de los estudiantes.')

@section('content')
    <style>
        .analytics-hero {
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

        .analytics-hero h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: -0.04em;
        }

        .analytics-hero p {
            margin: 0.75rem 0 0;
            color: rgba(255,255,255,0.9);
            max-width: 40rem;
            font-size: 0.95rem;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .export-btn {
            background: white;
            color: #1B5E46;
            padding: 0.85rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .export-btn:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-1px);
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 24px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .filter-form .form-group {
            margin-bottom: 0;
        }

        .filter-form .form-group label {
            margin-bottom: 0.5rem;
        }

        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            text-align: center;
            color: #999;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 1rem;
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
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            border-left: 4px solid #F8B803;
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1B5E46;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 11px;
            color: #6b7b6c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 1.5rem;
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
            border-radius: 14px;
            margin-bottom: 20px;
            border-left: 4px solid #f5c6cb;
        }

        @media (max-width: 1024px) {
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }

            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }

            .analytics-hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }

            .statistics-grid,
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .analytics-hero h1 {
                font-size: 1.4rem;
            }
        }
    </style>

    <div class="analytics-hero">
        <div>
            <h1>📊 Análisis de Información Física</h1>
            <p>Resumen claro y moderno para entender mejor el estado físico de los estudiantes.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('analytics.export-csv', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="export-btn">⬇️ Descargar CSV</a>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        label: 'Edad Promedio (años)',
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
            const ageRangeData = @json($statistics['ageRanges']);
            const ageRangeLabels = Object.keys(ageRangeData);
            const ageRangeValues = Object.values(ageRangeData);

            new Chart(document.getElementById('ageRangeChart'), {
                type: 'bar',
                data: {
                    labels: ageRangeLabels,
                    datasets: [{
                        label: 'Cantidad de Estudiantes',
                        data: ageRangeValues,
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
@endsection