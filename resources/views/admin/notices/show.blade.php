@extends('layouts.admin')

@section('title', 'Ver Aviso - GymUdec')
@section('page-title', '📢 ' . $notice->title)
@section('page-subtitle', 'Detalles del aviso y acciones disponibles.')
@section('page-actions')
    <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn-primary">✏️ Editar</a>
    <form method="POST" action="{{ route('admin.notices.destroy', $notice->id) }}" class="m-0 inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este aviso?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger">🗑️ Eliminar</button>
    </form>
@endsection

@section('content')
    <div class="page-card">
        <div class="mb-6">
            <p class="text-sm text-slate-500">Tipo: {{ $notice->type_label }} • Publicado por: {{ $notice->author?->name ?? 'Sistema' }} • {{ $notice->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="rounded-[30px] bg-slate-50 p-6 border border-emerald-100 shadow-sm">
            <p class="text-slate-700 leading-7">{{ $notice->content }}</p>
            <div class="mt-6 font-semibold text-emerald-950">Estado: <span class="text-slate-700">{{ $notice->is_active ? 'Activo' : 'Inactivo' }}</span></div>
        </div>
    </div>
@endsection
