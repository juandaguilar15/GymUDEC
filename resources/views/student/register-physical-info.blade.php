@extends('layouts.student')

@section('title', 'Registro de Información Física - GymUdec')
@section('page-title', 'Registro de Información Física')
@section('page-subtitle', 'Registra tu ficha para poder usar todas las funcionalidades del sistema')

@section('content')
    <div class="admin-card max-w-2xl mx-auto">
        <div class="flex items-start gap-4">
            <div class="text-emerald-600 mt-1"><x-icon name="list" /></div>
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-emerald-950">Bienvenido, {{ $user->name }}!</h2>
                <p class="text-slate-600 mt-2">Para acceder a todas las funcionalidades del sistema GymUdec necesitas registrar tu información física en la enfermería.</p>
            </div>
            <div class="ml-4">
                <span class="status-badge status-badge--warning">Información pendiente</span>
            </div>
        </div>

        <div class="mt-6 grid gap-4">
            <div class="page-card">
                <h3 class="font-semibold text-emerald-950">Datos que necesitarás registrar</h3>
                <ul class="mt-3 text-slate-700 list-disc pl-5">
                    <li>Edad</li>
                    <li>Fecha de nacimiento</li>
                    <li>Altura</li>
                    <li>Peso</li>
                    <li>Género</li>
                    <li>Condición de salud</li>
                    <li>Recomendaciones médicas</li>
                </ul>
            </div>

            <div class="grid gap-3">
                <a href="{{ route('home') }}" class="btn-tertiary">← Volver a Inicio</a>
                <button type="button" class="btn-primary" onclick="openContact()">Contactar Enfermería</button>
            </div>

            <p class="text-sm text-slate-500 mt-4">Una vez registres tu información, podrás acceder al dashboard completo y todas las funcionalidades del sistema.</p>
        </div>
    </div>

    @push('scripts')
    <script>
        function openContact() {
            alert('Por favor contacta con la enfermería del gimnasio GymUdec para registrar tu información física.\n\nCorreo: enfermeria@gymudec.com\nExt: 2024');
        }
    </script>
    @endpush
@endsection
