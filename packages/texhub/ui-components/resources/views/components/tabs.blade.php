@props([
    'tabs' => [],
    'active' => null,
])

@php
    $activeTab = $active ?? array_key_first($tabs);
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    <div class="flex gap-2 border-b border-gray-200">
        @foreach ($tabs as $key => $label)
            <button type="button" data-tab-target="#tab-{{ $key }}"
                class="ui-tab px-3 py-2 text-sm font-semibold transition border-b-2 -mb-px
                {{ $key === $activeTab ? 'text-blue-700 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
    <div class="pt-3">
        {{ $slot }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.ui-tab').forEach((btn) => {
            btn.addEventListener('click', () => {
                const wrapper = btn.closest('[data-tabs-wrapper]') || document;
                const target = document.querySelector(btn.dataset.tabTarget);

                if (!target) return;

                // deactivate
                btn.parentElement.querySelectorAll('.ui-tab').forEach((b) => {
                    b.classList.remove('text-blue-700', 'border-blue-600');
                    b.classList.add('text-gray-600', 'border-transparent');
                });
                wrapper.querySelectorAll('.ui-tab-content').forEach((p) => p.classList.add('hidden'));

                // activate
                btn.classList.add('text-blue-700', 'border-blue-600');
                btn.classList.remove('text-gray-600', 'border-transparent');
                target.classList.remove('hidden');
            });
        });
    });
</script>
