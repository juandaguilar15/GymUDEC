@props(['name'])

@php
    $icons = [
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0 0a4 4 0 100-8 4 4 0 000 8zM16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        'machine' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8m9-6H7a2 2 0 00-2 2v8a2 2 0 002 2h2l1 2h4l1-2h2a2 2 0 002-2V6a2 2 0 00-2-2z"/></svg>',
        'exercise' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2"/></svg>',
        'list' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>',
        'notice' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
        'chart' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 14V7m6 10V4m6 7v3"/></svg>',
        'pen' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 20h9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5z"/></svg>',
        'globe' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 100 20 10 10 0 000-20zM2 12h20M12 2c2.21 3 4 7 4 10s-1.79 7-4 10c-2.21-3-4-7-4-10s1.79-7 4-10z"/></svg>',
    ];
    echo $icons[$name] ?? '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="1.5"/></svg>';
@endphp
