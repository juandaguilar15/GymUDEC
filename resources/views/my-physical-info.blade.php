@extends('layouts.student')

@section('title', 'Mi Ficha Médica - GymUdec')
@section('page-title', 'Mi Ficha Médica')
@section('page-subtitle', 'Resumen clínico y recomendaciones relevantes para tu entrenamiento')

@section('fullwidth')@endsection
@section('content')
    <section class="w-full bg-gradient-to-r from-emerald-50 to-white rounded-2xl p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-emerald-950">Tu ficha médica</h2>
                <p class="text-slate-600 mt-1">Resumen clínico y recomendaciones esenciales para tu entrenamiento, con todo el registro de enfermería en un solo panel.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="btn-tertiary">Volver al dashboard</a>
            </div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto">
        <article class="bg-white rounded-2xl shadow-md border border-emerald-50 p-6">
            <div class="flex flex-col lg:flex-row lg:items-start gap-6 text-center lg:text-left">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-xl bg-emerald-600 grid place-items-center text-white text-3xl font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div>
                        <h3 class="text-2xl font-semibold text-emerald-950">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-medium">Permiso: {{ ucfirst($physicalInfo->permisos ?? 'Desconocido') }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:ml-auto w-full lg:w-1/3 grid grid-cols-3 gap-3">
                    <div class="p-3 bg-emerald-50 rounded-lg text-center">
                        <p class="text-xs text-slate-500 uppercase">Edad</p>
                        <p class="text-lg font-semibold text-emerald-950">{{ $physicalInfo->age ? number_format($physicalInfo->age,0) . ' años' : '—' }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-center">
                        <p class="text-xs text-slate-500 uppercase">Altura</p>
                        <p class="text-lg font-semibold text-emerald-950">{{ $physicalInfo->height ? number_format($physicalInfo->height,2) . ' m' : '—' }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-center">
                        <p class="text-xs text-slate-500 uppercase">Peso</p>
                        <p class="text-lg font-semibold text-emerald-950">{{ $physicalInfo->weight ? number_format($physicalInfo->weight,1) . ' kg' : '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Datos personales</p>
                    <div class="mt-2 text-sm text-slate-700">
                        <p><strong>Género:</strong> {{ ucfirst($physicalInfo->gender ?? 'No especificado') }}</p>
                        <p><strong>Fecha de nacimiento:</strong> {{ optional($physicalInfo->date_of_birth)->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs text-slate-500 uppercase font-semibold">IMC (Índice de Masa Corporal)</p>
                    <div class="mt-2 text-sm text-slate-700">
                        @php
                            $imc = null;
                            if(!empty($physicalInfo->height) && !empty($physicalInfo->weight)) {
                                $imc = $physicalInfo->weight / ($physicalInfo->height ** 2);
                                $imcFormatted = number_format($imc, 1);
                                if ($imc < 18.5) $imcCategory = 'Bajo peso';
                                elseif ($imc < 25) $imcCategory = 'Normal';
                                elseif ($imc < 30) $imcCategory = 'Sobrepeso';
                                else $imcCategory = 'Obesidad';
                            }
                        @endphp
                        <p class="text-lg font-semibold text-emerald-950">{{ $imc ? "$imcFormatted - $imcCategory" : '—' }}</p>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Permisos</p>
                    <div class="mt-2 text-sm text-slate-700">
                        <p class="text-lg font-semibold text-emerald-950">{{ ucfirst($physicalInfo->permisos ?? '—') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-slate-700">Notas clínicas / Historial de enfermería</h4>
                <div class="mt-3 grid gap-3">
                    <div class="p-4 bg-white rounded-lg border border-emerald-50">
                        <p class="text-sm text-slate-700 whitespace-pre-line">@if(trim($physicalInfo->condition ?? '') !== ''){{ $physicalInfo->condition }}@else Sin notas clínicas adicionales registradas.@endif</p>
                    </div>

                    <div class="p-4 bg-white rounded-lg border border-emerald-50">
                        <p class="text-sm text-slate-700">💡 <strong>Recomendaciones:</strong></p>
                        <p class="mt-2 text-sm text-slate-700">{{ $physicalInfo->recommendation ?? 'No hay recomendaciones registradas.' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-sm text-slate-500">Última actualización: {{ optional($physicalInfo->updated_at)->format('d/m/Y H:i') ?? '—' }}</div>
        </article>
    </div>
@endsection
