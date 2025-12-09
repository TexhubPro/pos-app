@props([
    'type' => 'info',
    'title' => null,
    'messages' => [],
])

@php
    $styles = [
        'success' => [
            'bg' => 'from-emerald-50/70 via-white to-white',
            'border' => 'border-emerald-200/80',
            'text' => 'text-emerald-900',
            'icon' => 'text-emerald-600',
        ],
        'error' => [
            'bg' => 'from-rose-50/70 via-white to-white',
            'border' => 'border-rose-200/80',
            'text' => 'text-rose-900',
            'icon' => 'text-rose-600',
        ],
        'warning' => [
            'bg' => 'from-amber-50/70 via-white to-white',
            'border' => 'border-amber-200/80',
            'text' => 'text-amber-900',
            'icon' => 'text-amber-600',
        ],
        'info' => [
            'bg' => 'from-blue-50/60 via-white to-white',
            'border' => 'border-blue-200/70',
            'text' => 'text-blue-900',
            'icon' => 'text-blue-600',
        ],
    ];

    $style = $styles[$type] ?? $styles['info'];

    $defaultTitles = [
        'success' => 'Готово',
        'error' => 'Ошибка',
        'warning' => 'Внимание',
        'info' => 'Информация',
    ];
    $resolvedTitle = $title ?? $defaultTitles[$type] ?? $defaultTitles['info'];
@endphp

<div
    class="relative overflow-hidden rounded-2xl border {{ $style['border'] }} bg-gradient-to-br {{ $style['bg'] }} px-5 py-4 shadow-md shadow-rose-100/40 backdrop-blur-sm">
    <div class="flex items-start gap-3 text-left">
        <div
            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-rose-100/70">
            @switch($type)
                @case('success')
                    <svg class="size-6 {{ $style['icon'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12l4 4L19 7" />
                    </svg>
                @break

                @case('warning')
                    <svg class="size-6 {{ $style['icon'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v2m0 4h.01" />
                        <path d="M10 3h4l8 14H2z" />
                    </svg>
                @break

                @case('error')
                    <svg class="size-6 {{ $style['icon'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v2m0 4h.01" />
                        <path d="M10 3h4l8 14H2z" />
                    </svg>
                @break

                @default
                    <svg class="size-6 {{ $style['icon'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9h.01" />
                        <path d="M11 12h1v4h1" />
                        <path d="M12 3a9 9 0 1 1-9 9a9 9 0 0 1 9-9z" />
                    </svg>
            @endswitch
        </div>
        <div class="flex-1 space-y-0.5 text-left leading-[1.1]">
            <p class="text-base font-semibold tracking-tight {{ $style['text'] }}">{{ $resolvedTitle }}</p>
            @if (count($messages))
                <div class="space-y-0.5 text-sm leading-5 {{ $style['text'] }}">
                    @foreach ($messages as $message)
                        <p class="leading-5">{{ $message }}</p>
                    @endforeach
                </div>
            @elseif (trim($slot))
                <div class="text-sm leading-5 {{ $style['text'] }}">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</div>
