<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full text-gray-900 antialiased bg-slate-50">
    <div class="flex min-h-screen bg-slate-50">
        <aside id="app-sidebar"
            class="fixed inset-y-0 start-0 z-40 w-60 transform transition-transform duration-300 bg-white border-e border-gray-200 shadow-xl -translate-x-full lg:translate-x-0 lg:static lg:shadow-none lg:transform-none">
            <div class="flex flex-col h-full">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between lg:hidden">
                    <div class="text-lg font-semibold">{{ $title ?? 'Dashboard' }}</div>
                    <button type="button" class="text-gray-500 hover:text-gray-700" data-sidebar-close
                        aria-label="Close sidebar">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <x-ui::sidebar />
                </div>
            </div>
        </aside>
        <div id="app-sidebar-backdrop"
            class="fixed inset-0 z-30 bg-slate-900/40 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden">
        </div>

        <div class="flex-1 flex flex-col min-h-screen">
            <header class="sticky top-0 z-20">
                <div class="px-4 lg:px-8 py-3">
                    <x-ui::header />
                </div>
            </header>
            <main class="flex-1 px-4 lg:px-8 py-6 max-w-[calc(100vw-0px)] lg:max-w-[calc(100vw-256px)] overflow-hidden">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('app-sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const open = () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('pointer-events-none');
                requestAnimationFrame(() => backdrop.classList.add('opacity-100'));
            };

            const close = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('pointer-events-none');
                backdrop.classList.remove('opacity-100');
            };

            document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isOpen = !sidebar.classList.contains('-translate-x-full');
                    isOpen ? close() : open();
                });
            });

            document.querySelectorAll('[data-sidebar-close]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    close();
                });
            });

            backdrop.addEventListener('click', close);
        })();
    </script>
</body>

</html>
