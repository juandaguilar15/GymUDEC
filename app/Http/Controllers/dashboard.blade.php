@extends('layouts.admin') {{-- Asumiendo que tienes un layout de admin --}}

@section('content')
<div class="container">
    <h1>⚙️ Panel de Control Administrativo</h1>

    <div class="stats-grid">
        {{-- Tarjetas de Estadísticas --}}
        <div class="stat-card">
            <div class="stat-value">{{ $stats['totalUsers'] }}</div>
            <div class="stat-label">Usuarios Totales</div>
        </div>
        {{-- ... resto de stats ... --}}
    </div>

    <div class="grid gap-6 lg:grid-cols-3 mt-8">
        {{-- TARJETAS DE ACCESO DIRECTO --}}
        <a href="{{ route('admin.users') }}" class="action-card">
            <div class="icon">👥</div>
            <h3>Gestión de Usuarios</h3>
            <p>Administra roles y permisos de la plataforma.</p>
        </a>

        <a href="{{ route('machines.index') }}" class="action-card">
            <div class="icon">🏋️</div>
            <h3>Inventario de Máquinas</h3>
            <p>Controla el estado y tipos de máquinas disponibles.</p>
        </a>

        <a href="{{ route('exercises.index') }}" class="action-card">
            <div class="icon">🏃</div>
            <h3>Biblioteca de Ejercicios</h3>
            <p>Crea y edita los ejercicios para las rutinas.</p>
        </a>

        <a href="{{ route('routines.index') }}" class="action-card">
            <div class="icon">📋</div>
            <h3>Moldes de Rutinas</h3>
            <p>Gestiona las rutinas maestras del sistema.</p>
        </a>

        <a href="{{ route('rutinas.index') }}" class="action-card">
            <div class="icon">✍️</div>
            <h3>Asignaciones</h3>
            <p>Asigna rutinas específicas a los estudiantes.</p>
        </a>
    </div>
</div>
@endsection