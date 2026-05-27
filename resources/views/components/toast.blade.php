@props(['type' => 'success', 'message' => ''])

<div class="fixed bottom-6 right-6 z-50 toast-message" role="status" aria-live="polite">
    <div class="rounded-lg shadow-lg px-4 py-3 bg-white border">
        <div class="flex items-center gap-3">
            <div class="text-emerald-600">
                @if($type === 'success')
                    <x-icon name="chart" />
                @else
                    <x-icon name="notice" />
                @endif
            </div>
            <div class="text-sm text-slate-700">{{ $message }}</div>
            <button type="button" class="close-toast ml-4 text-slate-400 hover:text-slate-600">✕</button>
        </div>
    </div>
</div>
