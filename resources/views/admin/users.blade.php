<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - GymUdec</title>
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
        
        .btn-reset {
            background: #e0e0e0;
            color: #333;
            padding: 0.7rem 1.5rem;
        }
        
        .btn-reset:hover {
            background: #d0d0d0;
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
        
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
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
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .modal-title {
            color: #1B5E46;
            font-size: 18px;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .modal-form select {
            padding: 0.7rem;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        
        .modal-form select:focus {
            outline: none;
            border-color: #1B5E46;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .modal-btn {
            flex: 1;
            padding: 0.7rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .modal-btn-save {
            background: #F8B803;
            color: white;
        }
        
        .modal-btn-save:hover {
            background: #e6a700;
        }
        
        .modal-btn-cancel {
            background: #e0e0e0;
            color: #333;
        }
        
        .modal-btn-cancel:hover {
            background: #d0d0d0;
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
        <h1>👥 Gestión de Usuarios</h1>
        
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        
        <!-- Búsqueda -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.users') }}" class="search-form">
                <div class="form-group">
                    <label for="search">Buscar por nombre o correo</label>
                    <input type="search" id="search" name="search" placeholder="Ingresa nombre o email..." value="{{ $search }}">
                </div>
                <button type="submit" class="btn-search">🔍 Buscar</button>
                <a href="{{ route('admin.users') }}" class="btn-reset">↻ Limpiar</a>
            </form>
        </div>
        
        @if ($users->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>👤 Nombre</th>
                            <th>📧 Correo</th>
                            <th>🏷️ Rol</th>
                            <th>📅 Registrado</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td class="email-cell">{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="action-link" onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')">✏️ Rol</button>
                                        <form action="{{ route('admin.delete-user', ['id' => $user->id]) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Está seguro de que desea eliminar a {{ $user->name }} y su información?');">
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
            @if ($users->hasPages())
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <p>📭 No se encontraron usuarios.</p>
            </div>
        @endif
    </div>

    <!-- Modal para cambiar rol -->
    <div id="roleModal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Cambiar Rol de Usuario</div>
            <form id="roleForm" method="POST" class="modal-form">
                @csrf
                <input type="hidden" id="userId" value="">
                
                <div>
                    <label>Nuevo Rol:</label>
                    <select name="role" required>
                        <option value="estudiante">Estudiante</option>
                        <option value="enfermero">Enfermero</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                
                <div class="modal-buttons">
                    <button type="submit" class="modal-btn modal-btn-save">💾 Guardar</button>
                    <button type="button" class="modal-btn modal-btn-cancel" onclick="closeRoleModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(userId, userName, currentRole) {
            const modal = document.getElementById('roleModal');
            const form = document.getElementById('roleForm');
            const userIdInput = document.getElementById('userId');
            
            userIdInput.value = userId;
            form.action = "/admin/users/" + userId + "/role";
            form.querySelector('select[name="role"]').value = currentRole;
            
            modal.classList.add('show');
        }
        
        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.remove('show');
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('roleModal');
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        }
    </script>
</body>
</html>
