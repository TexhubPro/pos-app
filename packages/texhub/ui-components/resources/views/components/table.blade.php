@props([
    'head' => null,
])

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <div class="overflow-hidden border border-gray-200 rounded-2xl shadow-sm bg-white">
                <table {{ $attributes->merge(['class' => 'ui-table min-w-full divide-y divide-gray-200 bg-white']) }}>
                    @if ($head)
                        <thead class="bg-gray-50">
                            {{ $head }}
                        </thead>
                    @endif
                    <tbody class="divide-y divide-gray-200">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ui-table th,
    .ui-table td {
        max-width: 14rem;
    }

    .ui-table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
