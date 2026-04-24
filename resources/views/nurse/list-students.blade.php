<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Estudiantes - GymUdec</title>
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
            align-items: center;
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
        
        .btn-primary {
            background: #F8B803;
            color: white;
        }
        
        .btn-primary:hover {
            background: #e6a700;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 28px;
            margin-bottom: 1.5rem;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
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
        
        .email-cell {
            color: #666;
            font-size: 12px;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-link {
            padding: 0.4rem 0.8rem;
            background: #1B5E46;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
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
            padding: 0.4rem 0.8rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .delete-btn:hover {
            background: #c0392b;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #999;
        }
        
        .empty-icon {
            font-size: 48px;
            margin-bottom: 1rem;
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
        
        .info-stats {
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
        }
        
        .stat-label {
            font-size: 12px;
            color: #999;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .navbar-actions {
                width: 100%;
                flex-direction: column;
            }
            
            .container {
                padding: 0 1rem;
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
            
            .info-stats {
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
            <a href="{{ route('nurse.search-student') }}" class="btn btn-primary">🔍 Buscar Estudiante</a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary">Salir</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>👥 Estudiantes Registrados</h1>
        
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        
        @if ($physicalInfos->count() > 0)
            <div class="info-stats">
                <div class="stat-card">
                    <div class="stat-value">{{ $physicalInfos->total() }}</div>
                    <div class="stat-label">Total de estudiantes registrados</div>
                </div>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>👤 Nombre</th>
                            <th>📧 Correo</th>
                            <th>📊 Edad</th>
                            <th>⚖️ Peso (kg)</th>
                            <th>📏 Altura (m)</th>
                            <th>♂️ Género</th>
                            <th>🔄 Última Actualización</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($physicalInfos as $info)
                            <tr>
                                <td><strong>{{ $info->user->name }}</strong></td>
                                <td class="email-cell">{{ $info->email }}</td>
                                <td>{{ $info->age }} años</td>
                                <td>{{ $info->weight }} kg</td>
                                <td>{{ $info->height }} m</td>
                                <td>{{ ucfirst($info->gender) }}</td>
                                <td>{{ $info->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('nurse.view-info', ['email' => $info->email]) }}" class="action-link">👁️ Ver</a>
                                        <a href="{{ route('nurse.physical-form', ['email' => $info->email]) }}" class="action-link">✏️ Editar</a>
                                        <form action="{{ route('nurse.delete-info', ['email' => $info->email]) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Está seguro que desea eliminar la información física de {{ $info->user->name }}?');">
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
            
            <!-- Pagination -->
            @if ($physicalInfos->hasPages())
                <div class="pagination">
                    {{ $physicalInfos->links() }}
                </div>
            @endif
        @else
            <div class="table-container">
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>No hay estudiantes registrados aún.</p>
                    <a href="{{ route('nurse.search-student') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        🔍 Buscar y Registrar Estudiante
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
