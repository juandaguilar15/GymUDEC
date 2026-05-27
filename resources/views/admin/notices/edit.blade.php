@extends('layouts.admin')

@section('title', 'Editar Aviso - GymUdec')
@section('page-title', '✏️ Editar Aviso')
@section('page-subtitle', 'Actualiza el contenido o el estado del aviso en el sistema.')

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
        <form method="POST" action="{{ route('admin.notices.update', $notice->id) }}" class="grid gap-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Título *</label>
                <input id="title" type="text" name="title" value="{{ old('title', $notice->title) }}" required class="border-emerald-100" />
            </div>

            <div class="form-group">
                <label for="content">Contenido *</label>
                <textarea id="content" name="content" rows="5" required class="border-emerald-100">{{ old('content', $notice->content) }}</textarea>
            </div>

            <div class="form-group">
                <label for="type">Tipo de aviso *</label>
                <select id="type" name="type" required class="border-emerald-100">
                    <option value="info" {{ old('type', $notice->type) === 'info' ? 'selected' : '' }}>Información</option>
                    <option value="warning" {{ old('type', $notice->type) === 'warning' ? 'selected' : '' }}>Advertencia</option>
                    <option value="success" {{ old('type', $notice->type) === 'success' ? 'selected' : '' }}>Éxito</option>
                    <option value="danger" {{ old('type', $notice->type) === 'danger' ? 'selected' : '' }}>Peligro</option>
                </select>
            </div>

            <div class="form-group">
                <label class="flex items-center gap-3 text-sm font-semibold text-emerald-900">
                    <input type="checkbox" name="is_active" value="1" class="rounded-lg border-emerald-100 focus:ring-emerald-500" {{ old('is_active', $notice->is_active) ? 'checked' : '' }}>
                    Aviso activo
                </label>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.notices.index') }}" class="btn-tertiary">Volver al listado</a>
                <a href="{{ route('dashboard') }}" class="btn-tertiary">Volver al Panel</a>
            </div>
        </form>
    </div>
@endsection
