@extends('layouts.nurse')

@section('title', 'Listado de Estudiantes - GymUdec')

@section('page-title', '👥 Estudiantes Registrados')

@section('page-subtitle', 'Visualiza el listado completo de estudiantes y gestiona sus datos físicos desde aquí.')

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if ($physicalInfos->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #F8B803;">
                <div style="font-size: 28px; font-weight: 700; color: #1B5E46;">{{ $physicalInfos->total() }}</div>
                <div style="font-size: 12px; color: #999; margin-top: 0.5rem;">Total de estudiantes registrados</div>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #F8B803;">
                <div style="font-size: 28px; font-weight: 700; color: #1B5E46;">{{ $physicalInfos->where('permisos', 'limitado')->count() }}</div>
                <div style="font-size: 12px; color: #999; margin-top: 0.5rem;">Con permisos limitados</div>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #F8B803;">
                <div style="font-size: 28px; font-weight: 700; color: #1B5E46;">{{ $physicalInfos->whereNotNull('condition')->count() }}</div>
                <div style="font-size: 12px; color: #999; margin-top: 0.5rem;">Con condiciones médicas</div>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #F8B803;">
                <div style="font-size: 28px; font-weight: 700; color: #1B5E46;">{{ round($physicalInfos->avg('weight'), 1) }} kg</div>
                <div style="font-size: 12px; color: #999; margin-top: 0.5rem;">Peso promedio</div>
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
                        <th>🔄 Última Actualización</th>
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
                                    <span style="color: #28a745;">✅ Libre</span>
                                @else
                                    <span style="color: #dc3545;">⚠️ Limitado</span>
                                @endif
                            </td>
                            <td>
                                @if($info->condition)
                                    <span style="color: #dc3545;" title="{{ $info->condition }}">⚕️ Sí</span>
                                @else
                                    <span style="color: #28a745;">✅ No</span>
                                @endif
                            </td>
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
            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; padding: 1rem; flex-wrap: wrap;">
                {{ $physicalInfos->links() }}
            </div>
        @endif
    @else
        <div class="table-container">
            <div style="text-align: center; padding: 3rem 2rem; color: #999;">
                <div style="font-size: 48px; margin-bottom: 1rem;">📭</div>
                <p>No hay estudiantes registrados aún.</p>
                <a href="{{ route('nurse.search-student') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    🔍 Buscar y Registrar Estudiante
                </a>
            </div>
        </div>
    @endif
@endsection
