@php
    use Illuminate\Support\Str;
@endphp

<x-layouts.dashboard>
    <div class="space-y-6">
        <div class="lg:flex items-center justify-between gap-3 flex-wrap">
            <div class="space-y-1">
                <x-ui::heading class="text-2xl">{{ __('Детали закупки') }}</x-ui::heading>
                <x-ui::subheading>{{ __('Просмотр данных и документов') }}</x-ui::subheading>
            </div>
            <div class="grid lg:grid-cols-3 gap-3 mt-3 lg:mt-0">
                <button type="button" id="open-upload-modal"
                    class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                    {{ __('Добавить документы') }}
                </button>
                <button type="button" id="open-receipt-modal"
                    class="inline-flex items-center justify-center h-11 rounded-xl bg-green-600 text-white px-4 font-semibold hover:bg-green-500 transition">
                    {{ __('Добавить поставку') }}
                </button>
                <a href="{{ route('purchases') }}"
                    class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                    {{ __('Вернуться к списку') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Основные данные') }}</div>
                    <div class="text-sm text-gray-700 space-y-1.5">
                        <div><span class="text-gray-500">{{ __('Дата:') }}</span>
                            <span
                                class="font-semibold text-gray-900">{{ $purchase->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Товар:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $purchase->product?->name ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Категория:') }}</span>
                            <span
                                class="font-semibold text-gray-900">{{ $purchase->product?->category?->name ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Фирма (доставщик):') }}</span>
                            <span class="font-semibold text-gray-900">{{ $purchase->firm?->name ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Коробок:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $purchase->box_qty ?? 0 }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Сумма товара:') }}</span>
                            <span class="font-semibold text-gray-900">
                                {{ number_format(($purchase->purchase_price ?? 0) * ($purchase->box_qty ?? 1), 2) }} $
                            </span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Итого с доставкой:') }}</span>
                            <span class="font-semibold text-blue-700">
                                {{ number_format(($purchase->purchase_price ?? 0) * ($purchase->box_qty ?? 1) + ($purchase->delivery_cn ?? 0) + ($purchase->delivery_tj ?? 0), 2) }}
                                $
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Цены и доставка') }}</div>
                    <div class="text-sm text-gray-700 space-y-1.5">
                        <div><span class="text-gray-500">{{ __('Цена за коробку:') }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($purchase->purchase_price, 2) }}
                                $</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Объём, куб:') }}</span>
                            <span
                                class="font-semibold text-gray-900">{{ number_format($purchase->delivery_cn_volume ?? 0, 2) }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Доставка Китай:') }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($purchase->delivery_cn, 2) }}
                                $</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Доставка Таджикистан:') }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($purchase->delivery_tj, 2) }}
                                $</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Себестоимость за 1 шт:') }}</span>
                            <span class="font-semibold text-blue-700">{{ number_format($purchase->cost_per_unit, 4) }}
                                $</span>
                        </div>
                        @php
                            $receivedTotal = $purchase->receipts->sum('box_qty');
                            $leftTotal = max(0, ($purchase->box_qty ?? 0) - $receivedTotal);
                        @endphp
                        <div><span class="text-gray-500">{{ __('Получено коробок:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $receivedTotal }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Осталось получить:') }}</span>
                            <span class="font-semibold {{ $leftTotal > 0 ? 'text-amber-600' : 'text-green-600' }}">
                                {{ $leftTotal }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Документы') }}</div>
                    @if ($purchase->documents->count())
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                            {{ $purchase->documents->count() }} {{ __('файл(ов)') }}
                        </span>
                    @endif
                </div>


                @if ($purchase->documents->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($purchase->documents as $doc)
                            @php
                                $isImage = Str::endsWith(Str::lower($doc->original_name), [
                                    '.jpg',
                                    '.jpeg',
                                    '.png',
                                    '.webp',
                                    '.gif',
                                ]);
                            @endphp
                            <div class="group border border-gray-200 rounded-xl p-3 flex items-center gap-3 bg-gray-50">
                                @if ($isImage)
                                    <button type="button"
                                        class="shrink-0 h-12 w-12 rounded-lg overflow-hidden ring-1 ring-gray-200"
                                        data-preview="{{ $doc->path }}">
                                        <img src="{{ $doc->path }}" alt="{{ $doc->original_name }}"
                                            class="h-full w-full object-cover">
                                    </button>
                                @else
                                    <div
                                        class="shrink-0 h-12 w-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500">
                                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 7h10M7 12h10m-9 5h5" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $doc->original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $doc->size ? number_format($doc->size / 1024, 1) . ' KB' : '' }}
                                    </p>
                                    <a href="{{ $doc->path }}" target="_blank"
                                        class="text-xs font-semibold text-blue-600 hover:underline">{{ __('Открыть') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-500">{{ __('Документов нет') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="rounded-2xl border mt-4 border-gray-200 bg-white p-4 shadow-sm space-y-3">
        @php
            $receivedTotal = $purchase->receipts->sum('box_qty');
            $leftTotal = max(0, ($purchase->box_qty ?? 0) - $receivedTotal);
        @endphp
        <div class="flex items-center justify-between flex-wrap gap-2">
            <p class="text-lg font-semibold text-gray-900">{{ __('Поставки по этой закупке') }}</p>
            <div class="text-sm text-gray-600">
                {{ __('Получено:') }} <span class="font-semibold text-gray-900">{{ $receivedTotal }}</span>
                · {{ __('Осталось:') }} <span
                    class="font-semibold {{ $leftTotal > 0 ? 'text-amber-600' : 'text-green-600' }}">{{ $leftTotal }}</span>
            </div>
        </div>
        @if ($purchase->receipts->count())
            <x-ui::table class="min-w-full">
                <x-slot:head>
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                            {{ __('Дата') }}</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                            {{ __('Коробок') }}</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                            {{ __('Пользователь') }}</th>
                    </tr>
                </x-slot:head>
                @foreach ($purchase->receipts as $receipt)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $receipt->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 whitespace-nowrap">
                            {{ $receipt->box_qty }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $receipt->user?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </x-ui::table>
        @else
            <div class="text-sm text-gray-500">{{ __('Поставок пока нет') }}</div>
        @endif
    </div>

    <div id="image-lightbox" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="relative max-w-5xl w-full px-6">
            <button type="button" id="lightbox-close"
                class="absolute -top-10 right-0 text-white text-2xl font-bold">&times;</button>
            <img id="lightbox-img" src="" alt="Preview" class="w-full rounded-2xl shadow-2xl">
        </div>
    </div>

    <div id="upload-modal"
        class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-sm z-50 px-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full p-6 relative">
            <button type="button" id="upload-close"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('Добавить документы') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('До 5 МБ каждый, можно несколько файлов') }}</p>
                </div>
                <form action="{{ route('purchases.documents.store', $purchase) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input id="files" name="files[]" type="file" multiple
                        class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600"
                        accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,.doc,.docx,.xls,.xlsx" required>
                    @error('files.*')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" id="upload-cancel"
                            class="inline-flex items-center justify-center h-10 rounded-xl bg-gray-100 text-gray-800 px-4 font-semibold hover:bg-gray-200 transition">
                            {{ __('Отмена') }}
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center h-10 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                            {{ __('Загрузить') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="receipt-modal"
        class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-sm z-50 px-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
            <button type="button" id="receipt-close"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('Добавить поставку') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Укажите сколько коробок получили по этой закупке') }}</p>
                </div>
                <form action="{{ route('purchases.receipts.store', $purchase) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="space-y-1.5">
                        <x-ui::label for="receipt_box_qty">{{ __('Коробок получено') }}</x-ui::label>
                        <x-ui::input id="receipt_box_qty" name="box_qty" type="number" min="1"
                            step="1" required />
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" id="receipt-cancel"
                            class="inline-flex items-center justify-center h-10 rounded-xl bg-gray-100 text-gray-800 px-4 font-semibold hover:bg-gray-200 transition">
                            {{ __('Отмена') }}
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center h-10 rounded-xl bg-green-600 text-white px-4 font-semibold hover:bg-green-500 transition">
                            {{ __('Добавить') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.getElementById('image-lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const lightboxClose = document.getElementById('lightbox-close');
            const uploadModal = document.getElementById('upload-modal');
            const openUpload = document.getElementById('open-upload-modal');
            const closeUpload = document.getElementById('upload-close');
            const cancelUpload = document.getElementById('upload-cancel');
            const receiptModal = document.getElementById('receipt-modal');
            const openReceipt = document.getElementById('open-receipt-modal');
            const closeReceipt = document.getElementById('receipt-close');
            const cancelReceipt = document.getElementById('receipt-cancel');

            document.querySelectorAll('[data-preview]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    lightboxImg.src = btn.dataset.preview;
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            });

            const close = () => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                lightboxImg.src = '';
            };

            lightboxClose.addEventListener('click', close);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    close();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });

            const closeUploadModal = () => {
                uploadModal.classList.add('hidden');
                uploadModal.classList.remove('flex');
            };

            openUpload?.addEventListener('click', () => {
                uploadModal.classList.remove('hidden');
                uploadModal.classList.add('flex');
            });

            closeUpload?.addEventListener('click', closeUploadModal);
            cancelUpload?.addEventListener('click', (e) => {
                e.preventDefault();
                closeUploadModal();
            });
            uploadModal?.addEventListener('click', (e) => {
                if (e.target === uploadModal) {
                    closeUploadModal();
                }
            });

            const closeReceiptModal = () => {
                receiptModal.classList.add('hidden');
                receiptModal.classList.remove('flex');
            };

            openReceipt?.addEventListener('click', () => {
                receiptModal.classList.remove('hidden');
                receiptModal.classList.add('flex');
            });
            closeReceipt?.addEventListener('click', closeReceiptModal);
            cancelReceipt?.addEventListener('click', (e) => {
                e.preventDefault();
                closeReceiptModal();
            });
            receiptModal?.addEventListener('click', (e) => {
                if (e.target === receiptModal) {
                    closeReceiptModal();
                }
            });
        });
    </script>
</x-layouts.dashboard>
