<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Royal LMS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100">
    <div x-data="{ collapsed: window.innerWidth < 1024 }" x-init="window.addEventListener('resize', () => {
        if (window.innerWidth < 1024) collapsed = true
    })" class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main --}}
        <div class="flex-1 flex flex-col">
            @include('layouts.topbar')

            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
