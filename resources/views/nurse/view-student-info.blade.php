@extends('layouts.nurse')

@section('title', 'Información del Estudiante - GymUdec')

@section('page-title', '👁️ Información del Estudiante')

@section('page-subtitle', 'Revisa el historial y datos físicos del estudiante con una vista ordenada y clara.')

@section('content')
    <div class="page-messaging">
        <strong>Información del estudiante:</strong> Revisa todos los datos físicos y médicos. Usa los botones abajo para editar o eliminar si es necesario.
    </div>

    <div class="page-card">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-emerald-900">👤 Perfil del Estudiante</h2>
            <p class="text-slate-600">Información completa y organizada para evaluación médica.</p>
        </div>

        <div class="rounded-3xl bg-slate-50 border border-amber-100 p-6 mb-8">
            <h3 class="text-xs uppercase tracking-[0.2em] font-semibold text-emerald-900 mb-3">Información del Estudiante</h3>
            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-6">
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Edad</p>
                <p class="text-xl font-semibold text-emerald-950">{{ number_format($physicalInfo->age, 0) }} años</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Género</p>
                <p class="text-xl font-semibold text-emerald-950">{{ ucfirst($physicalInfo->gender) }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Fecha de Nacimiento</p>
                <p class="text-xl font-semibold text-emerald-950">{{ $physicalInfo->date_of_birth->format('d/m/Y') }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Permisos</p>
                <p class="text-xl font-semibold text-emerald-950">
                    @if($physicalInfo->permisos === 'libre')
                        ✅ Libre
                    @else
                        ⚠️ Limitado
                    @endif
                </p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-6">
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Altura</p>
                <p class="text-xl font-semibold text-emerald-950">{{ number_format($physicalInfo->height, 2) }} m</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Peso</p>
                <p class="text-xl font-semibold text-emerald-950">{{ number_format($physicalInfo->weight, 1) }} kg</p>
            </div>
        </div>

        <div class="rounded-3xl bg-slate-50 p-6 mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">IMC (Índice de Masa Corporal)</p>
            @php
                $imc = $physicalInfo->weight / ($physicalInfo->height ** 2);
                $imcFormatted = number_format($imc, 1);
                $imcCategory = '';
                if ($imc < 18.5) $imcCategory = 'Bajo peso';
                elseif ($imc < 25) $imcCategory = 'Normal';
                elseif ($imc < 30) $imcCategory = 'Sobrepeso';
                else $imcCategory = 'Obesidad';
            @endphp
            <p class="text-xl font-semibold text-emerald-950">{{ $imcFormatted }} - {{ $imcCategory }}</p>
        </div>

        <div class="rounded-3xl bg-slate-50 p-6 mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">⚕️ Condición Médica / Limitaciones</p>
            <p class="text-sm leading-7 text-slate-700">
                @if ($physicalInfo->condition)
                    {{ $physicalInfo->condition }}
                @else
                    No reporta condiciones médicas o limitaciones físicas.
                @endif
            </p>
        </div>

        <div class="rounded-3xl bg-slate-50 p-6 mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">💡 Recomendaciones Médicas</p>
            <p class="text-sm leading-7 text-slate-700">
                @if ($physicalInfo->recommendation)
                    {{ $physicalInfo->recommendation }}
                @else
                    No hay recomendaciones médicas específicas.
                @endif
            </p>
        </div>

        <p class="text-sm text-slate-500 mb-8"><strong>Última actualización:</strong> {{ $physicalInfo->updated_at->format('d/m/Y H:i') }}</p>

        <div class="flex flex-wrap gap-4">
            <a href="{{ route('nurse.physical-form', ['email' => $user->email]) }}" class="btn-primary">✏️ Editar Información</a>
            <form action="{{ route('nurse.delete-info', ['email' => $user->email]) }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar la información física de este estudiante? Esta acción no se puede deshacer.')">🗑️ Eliminar Registro</button>
            </form>
            <a href="{{ route('nurse.list-students') }}" class="btn-tertiary">← Volver al Listado</a>
        </div>
    </div>
@endsection
