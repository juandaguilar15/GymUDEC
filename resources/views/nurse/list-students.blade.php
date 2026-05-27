@extends('layouts.nurse')

@section('title', 'Listado de Estudiantes - GymUdec')

@section('page-title', '👥 Estudiantes Registrados')

@section('page-subtitle', 'Visualiza el listado completo de estudiantes y gestiona sus datos físicos desde aquí.')

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="page-messaging">
        <strong>Atención:</strong> utiliza los botones de acción para ver el perfil, editar datos físicos o eliminar el registro. Los colores ayudan a identificar cada paso.
    </div>

    <div class="breadcrumb-actions">
        <p class="text-slate-600 max-w-2xl">Aquí tienes el listado de estudiantes registrados. Revisa permisos y condiciones desde la tabla, luego selecciona la acción deseada.</p>
        <a href="{{ route('nurse.search-student') }}" class="btn-primary">➕ Buscar / Registrar estudiante</a>
    </div>

    @if ($physicalInfos->count() > 0)
        <div class="grid gap-4 mb-8 xl:grid-cols-4 sm:grid-cols-2">
            <div class="rounded-3xl bg-white border border-emerald-100 shadow-sm p-6">
                <div class="text-3xl font-bold text-emerald-950">{{ $physicalInfos->total() }}</div>
                <div class="mt-2 text-sm text-slate-500">Total de estudiantes registrados</div>
            </div>
            <div class="rounded-3xl bg-white border border-emerald-100 shadow-sm p-6">
                <div class="text-3xl font-bold text-emerald-950">{{ $physicalInfos->where('permisos', 'limitado')->count() }}</div>
                <div class="mt-2 text-sm text-slate-500">Con permisos limitados</div>
            </div>
            <div class="rounded-3xl bg-white border border-emerald-100 shadow-sm p-6">
                <div class="text-3xl font-bold text-emerald-950">{{ $physicalInfos->whereNotNull('condition')->count() }}</div>
                <div class="mt-2 text-sm text-slate-500">Con condiciones médicas</div>
            </div>
            <div class="rounded-3xl bg-white border border-emerald-100 shadow-sm p-6">
                <div class="text-3xl font-bold text-emerald-950">{{ round($physicalInfos->avg('weight'), 1) }} kg</div>
                <div class="mt-2 text-sm text-slate-500">Peso promedio</div>
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
                        <th>🩺 Permisos</th>
                        <th>⚕️ Condición</th>
                        <th>🔄 Actualizado</th>
                        <th>⚙️ Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($physicalInfos as $info)
                        <tr>
                            <td><strong>{{ $info->user->name }}</strong></td>
                            <td class="email-cell">{{ $info->email }}</td>
                            <td>{{ number_format($info->age, 0) }} años</td>
                            <td>{{ number_format($info->weight, 1) }} kg</td>
                            <td>{{ number_format($info->height, 2) }} m</td>
                            <td>{{ ucfirst($info->gender) }}</td>
                            <td>
                                @if($info->permisos === 'libre')
                                    <span class="status-badge status-badge--success">✅ Libre</span>
                                @else
                                    <span class="status-badge status-badge--warning">⚠️ Limitado</span>
                                @endif
                            </td>
                            <td>
                                @if($info->condition)
                                    <span class="status-badge status-badge--danger" title="{{ $info->condition }}">⚕️ {{ $info->condition }}</span>
                                @else
                                    <span class="status-badge status-badge--success">✅ Sin condiciones</span>
                                @endif
                            </td>
                            <td>{{ $info->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('nurse.view-info', ['email' => $info->email]) }}" class="btn-warning">👁️ Ver</a>
                                    <a href="{{ route('nurse.physical-form', ['email' => $info->email]) }}" class="btn-primary">✏️ Editar</a>
                                    <form action="{{ route('nurse.delete-info', ['email' => $info->email]) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar la información física de este estudiante? Esta acción no se puede deshacer.')">🗑️ Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($physicalInfos->hasPages())
            <div class="flex flex-wrap justify-center gap-2 mt-8 p-4">
                {{ $physicalInfos->links() }}
            </div>
        @endif
    @else
        <div class="page-card text-center">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-slate-600">No hay estudiantes registrados aún.</p>
            <a href="{{ route('nurse.search-student') }}" class="btn-primary mt-5 inline-flex">🔍 Buscar y Registrar Estudiante</a>
        </div>
    @endif
@endsection
