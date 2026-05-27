@extends('layouts.nurse')

@section('title', 'Buscar Estudiante - GymUdec')

@section('page-title', '👨‍⚕️ Búsqueda de Estudiante')

@section('page-subtitle', 'Busca un estudiante por correo institucional y gestiona su información física.')

@section('content')
    @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-card">
        <h2 class="text-emerald-900 text-2xl font-semibold mb-4">🔍 Iniciar Evaluación</h2>
        <p class="text-slate-600 mb-6">Introduce el correo institucional del estudiante para encontrar su perfil y continuar con el seguimiento.</p>

        <form action="{{ route('nurse.search') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Correo del Estudiante</label>
                <input type="email" id="email" name="email" placeholder="estudiante@ucundinamarca.edu.co" value="{{ old('email') }}" required>
                <p class="info-text">Ingresa el correo institucional del estudiante.</p>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="btn-primary">🔍 Buscar Estudiante</button>
                <a href="{{ route('nurse.list-students') }}" class="btn-tertiary">👥 Ver Listado</a>
            </div>
        </form>
    </div>
@endsection
