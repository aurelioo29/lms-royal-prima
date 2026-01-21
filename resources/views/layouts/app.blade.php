<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>LMS Royal Prima</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #e2e8f0;
        }

        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #e2e8f0;
            background: #f8fafc;
        }
    </style>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</head>

<body class="text-slate-800 bg-gradient-to-br from-rose-50 via-amber-50 to-slate-100">

    {{-- Background ornaments (subtle) --}}
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-rose-400/15 blur-3xl"></div>
        <div class="absolute -bottom-48 -right-40 h-[28rem] w-[28rem] rounded-full bg-amber-400/15 blur-3xl"></div>

        <div class="absolute inset-0 opacity-[0.07]"
            style="background-image: radial-gradient(currentColor 1px, transparent 1px); background-size: 24px 24px; color: #991b1b;">
        </div>
    </div>

    <div x-data="{ collapsed: false, sidebarOpen: false }" x-effect="document.body.classList.toggle('overflow-hidden', sidebarOpen)"
        class="min-h-screen">

        {{-- Backdrop (mobile) --}}
        <div x-show="sidebarOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-black/40 z-40 lg:hidden"
            @click="sidebarOpen = false">
        </div>

        {{-- MOBILE SIDEBAR --}}
        <aside x-show="sidebarOpen" x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur border-r border-slate-300/90 ring-1 ring-slate-200/60 flex flex-col lg:hidden">

            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-300/90">
                <img src="{{ asset('images/logo-royal.png') }}" class="h-12 w-auto" alt="LMS Royal Prima">
                <button @click="sidebarOpen = false" class="p-2 rounded-lg hover:bg-white/60 transition">✕</button>
            </div>

            @include('layouts.sidebar-content')
        </aside>

        {{-- DESKTOP SIDEBAR --}}
        <aside :class="collapsed ? 'w-16' : 'w-64'"
            class="hidden lg:flex fixed inset-y-0 left-0 z-50 bg-white/70 backdrop-blur border-r border-slate-300/90 ring-1 ring-slate-200/60 transition-all duration-300 flex-col overflow-hidden">

            <div x-show="!collapsed" x-transition.opacity
                class="h-16 flex items-center justify-center border-b border-slate-300/90">
                <img src="{{ asset('images/logo-royal.png') }}" class="h-12 w-auto" alt="LMS Royal Prima">
            </div>

            @include('layouts.sidebar-content')
        </aside>

        {{-- TOPBAR (FIXED, ALWAYS CLICKABLE) --}}
        <div :class="collapsed ? 'lg:left-16' : 'lg:left-64'" class="fixed top-0 right-0 left-0 lg:left-64 z-[60]">
            @include('layouts.topbar')
        </div>

        {{-- MAIN AREA --}}
        <div :class="collapsed ? 'lg:ml-16' : 'lg:ml-64'" class="transition-all duration-300">

            {{-- HEADER SLOT (below topbar) --}}
            @if (isset($header))
                <div class="pt-16 bg-white/80 backdrop-blur border-b border-slate-300/90">
                    <div class="px-6 py-4">
                        {{ $header }}
                    </div>
                </div>
            @endif

            {{-- Toast Notifications --}}
            @if (session('success'))
                <x-alert-popup type="success" :message="session('success')" />
            @endif
            @if (session('error'))
                <x-alert-popup type="error" :message="session('error')" />
            @endif
            @if (session('warning'))
                <x-alert-popup type="warning" :message="session('warning')" />
            @endif
            @if (session('info'))
                <x-alert-popup type="info" :message="session('info')" />
            @endif

            {{-- CONTENT --}}
            <main class="pt-16 p-6">
                {{ $slot }}
            </main>
        </div>

    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function confirmLogout(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Logout?',
                text: 'Anda akan keluar dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#121293',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>

</html>
