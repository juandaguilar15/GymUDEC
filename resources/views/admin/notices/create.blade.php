@extends('layouts.admin')

@section('title', 'Nuevo Aviso - GymUdec')
@section('page-title', '📢 Publicar Aviso')
@section('page-subtitle', 'Crea un nuevo comunicado para el sistema con alcance inmediato o diferido.')

@section('content')
    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mt-2 list-disc pl-5 space-y-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-card">
        <form method="POST" action="{{ route('admin.notices.store') }}" class="grid gap-6">
            @csrf
            <div class="form-group">
                <label for="title">Título *</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="border-emerald-100" placeholder="Título del aviso" />
            </div>

            <div class="form-group">
                <label for="content">Contenido *</label>
                <textarea id="content" name="content" rows="5" required class="border-emerald-100" placeholder="Escribe el aviso aquí">{{ old('content') }}</textarea>
            </div>

            <div class="form-group">
                <label for="type">Tipo de aviso *</label>
                <select id="type" name="type" required class="border-emerald-100">
                    <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Información</option>
                    <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Advertencia</option>
                    <option value="success" {{ old('type') === 'success' ? 'selected' : '' }}>Éxito</option>
                    <option value="danger" {{ old('type') === 'danger' ? 'selected' : '' }}>Peligro</option>
                </select>
                <p class="info-text">Elige el nivel de importancia del aviso.</p>
            </div>

            <div class="form-group">
                <label class="flex items-center gap-3 text-sm font-semibold text-emerald-900">
                    <input type="checkbox" name="notify_now" value="1" class="rounded-lg border-emerald-100 focus:ring-emerald-500" {{ old('notify_now') ? 'checked' : '' }}>
                    Enviar notificación ahora a todos los usuarios
                </label>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Publicar</button>
                <a href="{{ route('admin.notices.index') }}" class="btn-tertiary">Volver al listado</a>
                <a href="{{ route('dashboard') }}" class="btn-tertiary">Volver al Panel</a>
            </div>
        </form>
    </div>
@endsection
