@extends('layouts.student')

@section('page-title', 'Avisos')
@section('page-subtitle', 'Últimos avisos públicos y tu bandeja de notificaciones')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.45fr]">
        <section class="admin-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-emerald-950">Avisos Públicos</h2>
                <a href="{{ route('dashboard') }}" class="btn-tertiary">Volver al Dashboard</a>
            </div>

            @if($notices->count() > 0)
                <div class="space-y-4">
                    @foreach($notices as $notice)
                        <article class="page-card">
                            <div class="flex items-start gap-4">
                                <div class="text-emerald-600 mt-1"><x-icon name="notice" /></div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-emerald-950">{{ $notice->title }}</h3>
                                    <p class="text-slate-600 mt-2">{{ $notice->content }}</p>
                                    <p class="text-sm text-slate-500 mt-2">Publicado por: {{ optional($notice->author)->name ?? 'Administrador' }} · {{ $notice->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="text-slate-600">No hay avisos públicos en este momento.</p>
            @endif
        </section>

        <aside class="admin-card">
            <h3 class="text-lg font-semibold text-emerald-950 mb-3">Notificaciones</h3>
            @if($unreadNotifications && $unreadNotifications->count() > 0)
                <div class="space-y-3">
                    @foreach($unreadNotifications as $notification)
                        <article class="rounded-2xl bg-white p-4 border border-emerald-50 shadow-sm">
                            <h4 class="font-semibold text-emerald-950">{{ data_get($notification->data, 'title', 'Notificación') }}</h4>
                            <p class="text-sm text-slate-600">{{ data_get($notification->data, 'content') }}</p>
                            <p class="text-xs text-slate-400 mt-2">Recibida: {{ $notification->created_at->diffForHumans() }}</p>
                            <form method="POST" action="{{ route('student.notices.mark-read', ['notification' => $notification->id]) }}" class="mt-3">
                                @csrf
                                <button type="submit" class="btn-primary">Marcar como leída</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="text-slate-600">No tienes notificaciones nuevas.</p>
            @endif
        </aside>
    </div>
@endsection
