@extends('layouts.admin')

@section('title', 'Avisos - GymUdec')
@section('page-title', '📢 Avisos')
@section('page-subtitle', 'Revisa y administra los comunicados activos del sistema.')
@section('page-actions')
    <a href="{{ route('admin.notices.create') }}" class="btn-primary">➕ Nuevo aviso</a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="page-card">
        @foreach($notices as $notice)
            <div class="notice-item">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-emerald-950">{{ $notice->title }}</h3>
                        <p class="text-sm text-slate-500">Tipo: {{ $notice->type_label }} • Publicado por: {{ $notice->author?->name ?? 'Sistema' }} • {{ $notice->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="status-badge {{ $notice->is_active ? 'status-badge--success' : 'status-badge--danger' }}">{{ $notice->is_active ? 'Activo' : 'Inactivo' }}</span>
                        <a href="{{ route('admin.notices.show', $notice->id) }}" class="btn-tertiary">Ver</a>
                        <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn-secondary">Editar</a>
                        <form method="POST" action="{{ route('admin.notices.destroy', $notice->id) }}" class="m-0" onsubmit="return confirm('¿Seguro que deseas eliminar este aviso?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
                <p class="text-slate-600 mt-4">{{ $notice->content }}</p>
            </div>
        @endforeach

        <div class="mt-6">
            {{ $notices->links() }}
        </div>
    </div>
@endsection
