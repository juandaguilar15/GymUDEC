@props(['href' => '#', 'icon' => 'list', 'title' => '', 'text' => ''])

<a href="{{ $href }}" class="action-card group">
    <div class="dashboard-action-card-icon">
        <x-icon :name="$icon" />
    </div>
    <div>
        <h3 class="dashboard-action-card-title">{{ $title }}</h3>
        <p class="dashboard-action-card-text">{{ $text }}</p>
    </div>
</a>
