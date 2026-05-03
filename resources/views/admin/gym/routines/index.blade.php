<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Rutinas - GymUdec</title>
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
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .btn-primary {
            background: #1B5E46;
            color: white;
            padding: 0.7rem 1.5rem;
        }
        
        .btn-primary:hover {
            background: #2a7a5e;
        }
        
        .gym-nav {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            gap: 1rem;
        }
        
        .gym-nav a {
            padding: 0.6rem 1.5rem;
            background: #e0e0e0;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .gym-nav a.active {
            background: #1B5E46;
            color: white;
        }
        
        .gym-nav a:hover {
            background: #1B5E46;
            color: white;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .search-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .search-form {
            display: flex;
            gap: 1rem;
            align-items: end;
        }
        
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        label {
            font-weight: 600;
            color: #1B5E46;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }
        
        input[type="search"] {
            padding: 0.7rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        
        input[type="search"]:focus {
            outline: none;
            border-color: #1B5E46;
        }
        
        .btn-search {
            background: #F8B803;
            color: white;
            padding: 0.7rem 1.5rem;
        }
        
        .btn-search:hover {
            background: #e6a700;
        }
        
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
        
        .type-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .type-fuerza {
            background: #f8d7da;
            color: #721c24;
        }
        
        .type-cardio {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .type-mixto {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-publica {
            background: #d4edda;
            color: #155724;
        }
        
        .status-privada {
            background: #fff3cd;
            color: #856404;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .action-link {
            padding: 0.3rem 0.6rem;
            background: #1B5E46;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .action-link:hover {
            background: #2a7a5e;
        }
        
        .delete-form {
            display: inline;
            margin: 0;
        }
        
        .delete-btn {
            padding: 0.3rem 0.6rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .delete-btn:hover {
            background: #c0392b;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding: 1rem;
            flex-wrap: wrap;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.5rem 0.8rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #1B5E46;
            font-size: 12px;
        }
        
        .pagination a:hover {
            background: #1B5E46;
            color: white;
        }
        
        .pagination .active span {
            background: #1B5E46;
            color: white;
            border-color: #1B5E46;
        }
        
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 8px;
            text-align: center;
            color: #999;
        }
        
        .exercise-count {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            background: #f0f0f0;
            border-radius: 3px;
            font-size: 12px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .header-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 0.7rem;
            }
            
            .actions {
                flex-direction: column;
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
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">← Volver al Panel</a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary">Salir</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Navegación entre Máquinas, Ejercicios y Rutinas -->
        <div class="gym-nav">
            <a href="{{ route('machines.index') }}">🏋️ Gestionar Máquinas</a>
            <a href="{{ route('exercises.index') }}">💪 Gestionar Ejercicios</a>
            <a href="{{ route('routines.index') }}" class="active">📋 Gestionar Rutinas</a>
            <a href="{{ route('rutinas.index') }}">📤 Asignar Rutinas</a>
        </div>
        
        <div class="header-actions">
            <h1>📋 Gestionar Rutinas</h1>
            <a href="{{ route('routines.create') }}" class="btn btn-primary">➕ Crear Rutina</a>
        </div>
        
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        
        <!-- Búsqueda -->
        <div class="search-section">
            <form method="GET" action="{{ route('routines.index') }}" class="search-form">
                <div class="form-group">
                    <label for="search">Buscar rutina</label>
                    <input type="search" id="search" name="search" placeholder="Ingresa nombre, objetivo o nivel..." value="{{ $search }}">
                </div>
                <button type="submit" class="btn btn-search">🔍 Buscar</button>
                <a href="{{ route('routines.index') }}" class="btn btn-secondary">↻ Limpiar</a>
            </form>
        </div>
        
        @if ($routines->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>📋 Nombre</th>
                            <th>🎯 Objetivo</th>
                            <th>📈 Nivel</th>
                            <th>⏱️ Duración</th>
                            <th>📅 Estado</th>
                            <th>🏋️ Ejercicios</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routines as $routine)
                            <tr>
                                <td><strong>{{ $routine->name }}</strong></td>
                                <td>
                                    <span class="type-badge type-{{ $routine->objective }}">{{ ucfirst($routine->objective) }}</span>
                                </td>
                                <td>{{ $routine->level }}</td>
                                <td>{{ $routine->duration_weeks }} semanas / {{ $routine->days_per_week }} días</td>
                                <td>
                                    <span class="status-badge status-{{ $routine->status }}">{{ ucfirst($routine->status) }}</span>
                                </td>
                                <td>
                                    <span class="exercise-count">{{ $routine->ejerciciosSeleccionados->count() }} ejercicio(s)</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('routines.edit', $routine->id) }}" class="action-link">✏️ Editar</a>
                                        <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Está seguro de que desea eliminar esta rutina?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn">🗑️ Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if ($routines->hasPages())
                <div class="pagination">
                    {{ $routines->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <p>📭 No se encontraron rutinas.</p>
            </div>
        @endif
    </div>
</body>
</html>
