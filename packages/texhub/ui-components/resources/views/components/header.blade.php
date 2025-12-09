<div class="w-full px-4 py-3 lg:px-4 lg:py-3 flex items-center justify-between bg-white/80 shadow-sm ring-1 ring-gray-100 rounded-2xl">
    <div class="flex items-center gap-3">
        <button class="lg:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200"
            aria-haspopup="dialog" aria-expanded="false" aria-controls="app-sidebar"
            aria-label="Toggle navigation" data-sidebar-toggle>
            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 6l16 0" />
                <path d="M4 12l16 0" />
                <path d="M4 18l16 0" />
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
            <div class="h-10 w-10 rounded-xl bg-blue-600 text-white inline-flex items-center justify-center font-bold text-lg">TH</div>
            <span class="text-lg font-semibold text-gray-900">TexHub</span>
        </a>
    </div>
    <div class="flex items-center gap-3">
        <div class="relative inline-flex">
            <button class="flex items-center gap-2" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown" data-user-dropdown-toggle="#user-menu">
                @php
                    $avatarPath = Auth::user()->avatar
                        ? asset('storage/' . Auth::user()->avatar)
                        : 'https://api.dicebear.com/9.x/initials/svg?seed=' . urlencode(Auth::user()->name ?? 'User');
                @endphp
                <img src="{{ $avatarPath }}" alt="Avatar"
                    class="h-10 w-10 object-cover rounded-full p-1 bg-white border-2 border-blue-600 hover:border-blue-500">
                <div class="text-left">
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ __('Онлайн') }}</p>
                </div>
            </button>

            <div id="user-menu"
                class="z-[99] hidden min-w-60 bg-white border border-gray-100 shadow-md rounded-xl mt-2 absolute right-0 top-full"
                role="menu" aria-orientation="vertical">
                <div class="p-1 space-y-0.5">
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base font-semibold text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                        href="{{ route('settings') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1.02 .622 2.347 .184 2.572 -1.065z" />
                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        </svg>
                        {{ __('Настройки') }}
                    </a>
                    <hr class="border-gray-200 my-1">
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base font-semibold text-gray-800 hover:bg-red-500 hover:text-white focus:text-white focus:outline-hidden focus:bg-red-500"
                        href="{{ route('logout') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                        </svg>
                        {{ __('Выйти') }}
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
