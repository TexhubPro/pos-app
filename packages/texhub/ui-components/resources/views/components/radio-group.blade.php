@props([
    'name',
    'options' => [],
    'model' => null,
    'class' => '',
])

<div class="flex flex-wrap gap-2 {{ $class }}">
    @foreach ($options as $value => $label)
        <label
            class="inline-flex items-center gap-2 px-3 h-11 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-800 cursor-pointer transition
                peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
            <input type="radio" name="{{ $name }}" value="{{ $value }}"
                @if ($model) wire:model="{{ $model }}" @endif
                class="peer sr-only">
            <span>{{ $label }}</span>
        </label>
    @endforeach
</div>
