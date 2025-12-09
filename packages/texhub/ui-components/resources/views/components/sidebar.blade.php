<div class="p-4 w-full min-w-52 max-w-52 flex flex-col justify-between h-full">
    <div class="space-y-1">
        <a href="{{ route('dashboard') }}">
            <p
                class="{{ Route::is('dashboard') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18v12H3z" />
                    <path d="M7 20h10" />
                    <path d="M9 16v4" />
                    <path d="M15 16v4" />
                    <path d="M8 12l3-3 2 2 3-3" />
                </svg>
                <span>{{ __('Управления') }}</span>
            </p>
        </a>
        <a href="{{ route('categories') }}">
            <p
                class="{{ Route::is('categories') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 4h6v6H4z" />
                    <path d="M14 4h6v6h-6z" />
                    <path d="M4 14h6v6H4z" />
                    <path d="M14 17h6" />
                    <path d="M17 14v6" />
                </svg>
                <span>{{ __('Категории') }}</span>
            </p>
        </a>
        <a href="{{ route('products') }}">
            <p
                class="{{ Route::is('products') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 3l8 4.5V21l-8 4.5L4 21V7.5z" />
                    <path d="M12 12l8-4.5" />
                    <path d="M12 12v9" />
                    <path d="M12 12L4 7.5" />
                </svg>
                <span>{{ __('Товары') }}</span>
            </p>
        </a>
        <a href="{{ route('purchases') }}">
            <p
                class="{{ Route::is('purchases') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M6 6h15l-1.5 9h-12z" />
                    <path d="M6 6L5 3H2" />
                    <circle cx="9" cy="19" r="1" />
                    <circle cx="17" cy="19" r="1" />
                </svg>
                <span>{{ __('Закупки') }}</span>
            </p>
        </a>
        <a href="{{ route('cashdesk') }}">
            <p
                class="{{ Route::is('cashdesk') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 7h18M6 7V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2" />
                    <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7" />
                    <path d="M8 11h8" />
                    <path d="M8 15h5" />
                </svg>
                <span>{{ __('Касса') }}</span>
            </p>
        </a>
        <a href="{{ route('sales') }}">
            <p
                class="{{ Route::is('sales') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16" />
                    <path d="M5 12h3l2 5 4-14 2 9h4" />
                </svg>
                <span>{{ __('Продажи') }}</span>
            </p>
        </a>
        <a href="{{ route('expenses') }}">
            <p
                class="{{ Route::is('expenses') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 5h18v14H3z" />
                    <path d="M7 9h10" />
                    <path d="M7 13h10" />
                    <path d="M7 17h6" />
                </svg>
                <span>{{ __('Расходы') }}</span>
            </p>
        </a>
        <a href="{{ route('bank') }}">
            <p
                class="{{ Route::is('bank') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 10l9-6 9 6" />
                    <path d="M5 10h14v10H5z" />
                    <path d="M7 14h2" />
                    <path d="M15 14h2" />
                    <path d="M12 14h2" />
                </svg>
                <span>{{ __('Банк') }}</span>
            </p>
        </a>
        <a href="{{ route('clients') }}">
            <p
                class="{{ Route::is('clients') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="7" r="3" />
                    <circle cx="17" cy="7" r="3" />
                    <path d="M4 21v-2a4 4 0 0 1 4-4h2" />
                    <path d="M14 15h2a4 4 0 0 1 4 4v2" />
                </svg>
                <span>{{ __('Клиенты') }}</span>
            </p>
        </a>

        <a href="{{ route('firms') }}">
            <p
                class="{{ Route::is('firms') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 21h18" />
                    <path d="M4 21V8l8-4 8 4v13" />
                    <path d="M9 21v-6h6v6" />
                    <path d="M9 9h6" />
                </svg>
                <span>{{ __('Фирмы') }}</span>
            </p>
        </a>
        <a href="{{ route('users') }}">
            <p
                class="{{ Route::is('users') ? 'text-blue-600' : 'text-gray-700 hover:bg-gray-200' }} min-w-full flex gap-3 items-center w-full text-base font-semibold p-3 hover:bg-gray-200 text-start rounded-xl duration-200">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 12a4 4 0 1 0 -4 -4a4 4 0 0 0 4 4z" />
                    <path d="M16 16a4 4 0 0 0 -8 0" />
                    <path d="M18 21v-2a3 3 0 0 0 -3 -3" />
                    <path d="M6 21v-2a3 3 0 0 1 3 -3" />
                </svg>
                <span>{{ __('Сотрудники') }}</span>
            </p>
        </a>
    </div>
