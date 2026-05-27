@php
    $user = auth()->user();
    $unread = $user ? $user->unreadNotifications()->limit(5)->get() : collect();
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
@endphp

<div style="position:relative;">
    <button id="notifications-toggle" style="background:transparent;border:none;cursor:pointer;position:relative;font-size:1.1rem;">
        🔔
        @if($unreadCount > 0)
            <span id="notif-badge" style="background:#e74c3c;color:#fff;border-radius:999px;padding:0.15rem 0.45rem;font-size:0.75rem;position:absolute;top:-6px;right:-6px;">{{ $unreadCount }}</span>
        @endif
    </button>

    <div id="notifications-panel" style="display:none;position:absolute;right:0;top:28px;width:320px;background:white;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.12);z-index:50;padding:0.5rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
            <strong>Notificaciones</strong>
            <form id="mark-all-form" method="POST" action="{{ route('student.notices.mark-all-read') }}" style="margin:0;">
                @csrf
                <button type="submit" style="background:#1B5E46;color:#fff;border:none;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.8rem;">Marcar todas</button>
            </form>
        </div>
        <div id="notifications-list" style="max-height:320px;overflow:auto;">
            @if($unread && $unread->count() > 0)
                @foreach($unread as $n)
                    <div style="padding:0.5rem;border-bottom:1px solid #f1f1f1;">
                        <div style="font-weight:600;color:#1B5E46;">{{ data_get($n->data, 'title', 'Notificación') }}</div>
                        <div style="font-size:0.9rem;color:#444;">{{ Str::limit(data_get($n->data, 'content', ''), 120) }}</div>
                        <div style="font-size:0.75rem;color:#888;margin-top:0.25rem;">{{ $n->created_at->diffForHumans() }}</div>
                        <form method="POST" action="{{ route('student.notices.mark-read', ['notification' => $n->id]) }}" style="margin-top:0.4rem;">
                            @csrf
                            <button type="submit" style="background:#F8B803;color:#1B5E46;border:none;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.8rem;">Marcar como leída</button>
                        </form>
                    </div>
                @endforeach
            @else
                <div style="padding:0.5rem;color:#666;">No tienes notificaciones nuevas.</div>
            @endif
        </div>
        <div style="text-align:center;margin-top:0.5rem;">
            <a href="{{ route('student.notices.index') }}" style="text-decoration:none;color:#1B5E46;font-weight:600;">Ver todas</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const toggle = document.getElementById('notifications-toggle');
    const panel = document.getElementById('notifications-panel');

    toggle.addEventListener('click', function(e){
        e.stopPropagation();
        panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(){
        panel.style.display = 'none';
    });

    // Intercept mark-all form to call backend via fetch if desired
    const markAllForm = document.getElementById('mark-all-form');
    if(markAllForm){
        markAllForm.addEventListener('submit', function(ev){
            ev.preventDefault();
            fetch(markAllForm.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }})
            .then(r => r.json())
            .then(data => {
                if(data.success){
                    const badge = document.getElementById('notif-badge');
                    if(badge) badge.remove();
                    const list = document.getElementById('notifications-list');
                    list.innerHTML = '<div style="padding:0.5rem;color:#666;">No tienes notificaciones nuevas.</div>';
                }
            }).catch(()=>{});
        });
    }
});
</script>
