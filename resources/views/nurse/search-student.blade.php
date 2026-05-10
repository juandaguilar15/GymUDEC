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
        <h2>Buscar Estudiante</h2>
        <p>Introduce el correo institucional del estudiante para encontrar su perfil y continuar con el seguimiento.</p>

        <form action="{{ route('nurse.search') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Correo del Estudiante</label>
                <input type="email" id="email" name="email" placeholder="estudiante@ucundinamarca.edu.co" required>
                <p class="info-text">Ingresa el correo institucional del estudiante.</p>
            </div>

            <button type="submit" class="btn btn-primary">🔍 Buscar Estudiante</button>
            <a href="{{ route('nurse.list-students') }}" class="btn btn-dark" style="width:100%; margin-top: 12px; justify-content:center;">👥 Ver Listado de Estudiantes</a>
        </form>
    </div>
@endsection
