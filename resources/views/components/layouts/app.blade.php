<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Lido — Вход' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full text-gray-900 antialiased"
    style="
        background:
            radial-gradient(circle at 20% 20%, rgba(96, 165, 250, 0.14), transparent 35%),
            radial-gradient(circle at 80% 0%, rgba(129, 140, 248, 0.16), transparent 32%),
            radial-gradient(circle at 60% 80%, rgba(52, 211, 153, 0.12), transparent 28%),
            linear-gradient(135deg, #f8fafc 0%, #fdfefe 50%, #f3f4f6 100%);
    ">
    <div class="relative min-h-screen">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 top-10 h-80 w-80 rounded-full bg-white/40 blur-3xl mix-blend-soft-light"></div>
            <div class="absolute right-[-8rem] top-24 h-[28rem] w-[28rem] rounded-full bg-white/30 blur-[80px] mix-blend-soft-light"></div>
            <div class="absolute left-20 bottom-[-8rem] h-72 w-72 rounded-full bg-white/25 blur-[72px] mix-blend-soft-light"></div>
        </div>
        <main class="relative z-10">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
