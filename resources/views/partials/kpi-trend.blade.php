@if($trend['direction'] == 'up')

    <small class="text-success fw-semibold">
        <i class="bi bi-arrow-up-right"></i>
        {{ $trend['label'] }}
        {{ $text }}
    </small>

@elseif($trend['direction'] == 'down')

    <small class="text-danger fw-semibold">
        <i class="bi bi-arrow-down-right"></i>
        {{ $trend['label'] }}
        {{ $text }}
    </small>

@else

    <small class="text-muted fw-semibold">
        <i class="bi bi-dash-lg"></i>
        {{ $trend['label'] }}
        {{ $text }}
    </small>

@endif