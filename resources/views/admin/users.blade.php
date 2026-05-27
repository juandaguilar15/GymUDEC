@extends('layouts.admin')

@section('title', 'Gestión de Usuarios - GymUdec')
@section('page-title', '👥 Gestión de Usuarios')
@section('page-subtitle', 'Usa los filtros para encontrar usuarios por nombre, correo o rol dentro del sistema.')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="page-card">
        <div class="breadcrumb-actions">
            <p class="text-slate-600">Mostrando <strong>{{ $users->total() }}</strong> usuario(s). Filtra por nombre, correo o rol para encontrar el registro correcto.</p>
        </div>

        <form method="GET" action="{{ route('admin.users') }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_1fr_auto] items-end mb-6">
            <div class="form-group">
                <label for="search">Buscar por nombre o correo</label>
                <input type="search" id="search" name="search" value="{{ $search }}" placeholder="Ingresa nombre o correo..." class="border-emerald-100" />
            </div>
            <div class="form-group">
                <label for="role">Filtrar por rol</label>
                <select id="role" name="role" class="border-emerald-100">
                    <option value="">Todos los roles</option>
                    <option value="estudiante" {{ $role === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="enfermero" {{ $role === 'enfermero' ? 'selected' : '' }}>Enfermero</option>
                    <option value="administrador" {{ $role === 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">🔍 Buscar</button>
            <a href="{{ route('admin.users') }}" class="btn-tertiary">↻ Limpiar</a>
        </form>

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
                                    <span class="status-badge status-badge--{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        @if(auth()->id() === $user->id)
                                            <span class="action-link" style="cursor: default; background: #6c757d;">👤 Identificado</span>
                                            <button type="button" class="action-link" onclick="alert('No puedes cambiar tu propio rol desde aquí. Usa otro administrador o actualiza tu rol en la base de datos.')">✏️ Rol</button>
                                        @else
                                            <button type="button" class="action-link" onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')">✏️ Rol</button>
                                            <form action="{{ route('admin.delete-user', ['id' => $user->id]) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">🗑️ Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="flex justify-center gap-2 mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <p class="text-lg">📭 No se encontraron usuarios.</p>
                <p class="text-slate-600 mt-3">{{ $search || $role ? 'Intenta limpiar los filtros o buscar otro término.' : 'Aún no hay usuarios registrados.' }}</p>
            </div>
        @endif
    </div>

    <div id="roleModal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Cambiar Rol de Usuario</div>
            <form id="roleForm" method="POST" class="modal-form">
                @csrf
                <input type="hidden" id="userId" value="">
                <div>
                    <label class="text-sm font-semibold text-emerald-900">Nuevo Rol:</label>
                    <select name="role" required class="border-emerald-100 rounded-2xl px-4 py-3 w-full">
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
@endsection

@push('scripts')
<script>
    function openRoleModal(userId, userName, currentRole) {
        const modal = document.getElementById('roleModal');
        const form = document.getElementById('roleForm');
        const userIdInput = document.getElementById('userId');

        userIdInput.value = userId;
        form.action = `/admin/users/${userId}/role`;
        form.querySelector('select[name="role"]').value = currentRole;
        modal.classList.add('show');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.remove('show');
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('roleModal');
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    });
</script>
@endpush
