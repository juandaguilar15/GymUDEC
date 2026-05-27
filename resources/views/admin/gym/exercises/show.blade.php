@extends('layouts.admin')

@section('title', 'Ver Ejercicio - GymUdec')
@section('page-title', $exercise->name)
@section('page-subtitle', 'Detalle del ejercicio, máquina asociada y multimedia disponible.')
@section('page-actions')
    <a href="{{ route('exercises.edit', $exercise->id) }}" class="btn-primary">✏️ Editar</a>
@endsection

@section('content')
    <div class="page-card">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Ejercicio</p>
                        <h2 class="text-2xl font-bold text-emerald-950">{{ $exercise->name }}</h2>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('exercises.index') }}" class="btn-tertiary">← Volver</a>
                        <form method="POST" action="{{ route('exercises.destroy', $exercise->id) }}" class="m-0" onsubmit="return confirm('¿Deseas eliminar este ejercicio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-emerald-50 p-6">
                        <p class="text-sm text-slate-500">Tipo</p>
                        <p class="mt-2 font-semibold text-emerald-900">{{ ucfirst($exercise->type) }}</p>
                    </div>
                    <div class="rounded-3xl bg-emerald-50 p-6">
                        <p class="text-sm text-slate-500">Máquina</p>
                        <p class="mt-2 font-semibold text-emerald-900">{{ $exercise->machine->name ?? 'Sin asignar' }}</p>
                    </div>
                    <div class="rounded-3xl bg-emerald-50 p-6">
                        <p class="text-sm text-slate-500">Formato</p>
                        <p class="mt-2 font-semibold text-emerald-900">{{ $exercise->exercise_format === 'duration' ? 'Duración' : 'Series y repeticiones' }}</p>
                    </div>
                    <div class="rounded-3xl bg-emerald-50 p-6">
                        <p class="text-sm text-slate-500">Grupo Muscular</p>
                        <p class="mt-2 font-semibold text-emerald-900">{{ $exercise->muscle_group }}</p>
                    </div>
                </div>

                <div class="rounded-[30px] bg-white border border-emerald-100 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-emerald-950 mb-3">Descripción</h3>
                    <p class="text-slate-700">{{ $exercise->description }}</p>
                </div>
            </div>

            <div class="space-y-6">
                @php
                    $img = null;
                    if ($exercise->image_url) {
                        if (filter_var($exercise->image_url, FILTER_VALIDATE_URL)) {
                            $img = $exercise->image_url;
                        } elseif (file_exists(storage_path('app/public/' . $exercise->image_url))) {
                            $img = asset('storage/' . $exercise->image_url);
                        } elseif (file_exists(public_path($exercise->image_url))) {
                            $img = asset($exercise->image_url);
                        }
                    }

                    $media = null;
                    if ($exercise->media_url) {
                        if (filter_var($exercise->media_url, FILTER_VALIDATE_URL)) {
                            $media = $exercise->media_url;
                        } elseif (file_exists(storage_path('app/public/' . $exercise->media_url))) {
                            $media = asset('storage/' . $exercise->media_url);
                        } elseif (file_exists(public_path($exercise->media_url))) {
                            $media = asset($exercise->media_url);
                        }
                    }
                @endphp

                @if ($img)
                    <div class="rounded-[30px] bg-white border border-emerald-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 mb-4">Imagen</p>
                        <img src="{{ $img }}" alt="Imagen del ejercicio" class="w-full rounded-3xl object-cover" />
                    </div>
                @endif

                @if ($media)
                    <div class="rounded-[30px] bg-white border border-emerald-100 p-6 shadow-sm">
                        <p class="text-sm text-slate-500 mb-4">Video</p>
                        <video controls class="w-full rounded-3xl">
                            <source src="{{ $media }}" type="video/mp4" />
                            Tu navegador no soporta video.
                        </video>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection