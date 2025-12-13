<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Royal LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-800">

    <div x-data="{
        collapsed: false,
        sidebarOpen: false
    }" x-effect="document.body.classList.toggle('overflow-hidden', sidebarOpen)"
        class="min-h-screen">

        <!-- ================= BACKDROP (MOBILE) ================= -->
        <div x-show="sidebarOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-black/40 z-40 lg:hidden"
            @click="sidebarOpen = false"></div>

        <!-- ================= MOBILE SIDEBAR (DRAWER) ================= -->
        <aside x-show="sidebarOpen" x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col lg:hidden">
            <!-- Mobile Header -->
            <div class="h-16 flex items-center justify-between px-4 border-b">
                <img src="{{ asset('images/logo-royal.png') }}" class="h-12 w-auto">
                <button @click="sidebarOpen = false" class="p-2 rounded hover:bg-slate-100">✕</button>
            </div>

            @include('layouts.sidebar-content')
        </aside>

        <!-- ================= DESKTOP SIDEBAR ================= -->
        <aside :class="collapsed ? 'w-16' : 'w-64'"
            class="hidden lg:flex fixed inset-y-0 left-0 bg-white border-r border-slate-200 transition-all duration-300 flex-col overflow-hidden">
            <!-- Logo (ONLY expanded) -->
            <div x-show="!collapsed" x-transition.opacity class="h-16 flex items-center justify-center border-b">
                <img src="{{ asset('images/logo-royal.png') }}" class="h-12 w-auto">
            </div>

            @include('layouts.sidebar-content')
        </aside>

        <!-- ================= MAIN AREA ================= -->
        <div :class="collapsed ? 'lg:ml-16' : 'lg:ml-64'" class="transition-all duration-300">
            <!-- HEADER -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-4">
                <div class="flex items-center gap-3">
                    <!-- Mobile Toggle -->
                    <button class="lg:hidden p-2 rounded hover:bg-slate-100" @click="sidebarOpen = true">
                        ☰
                    </button>

                    <!-- Desktop Toggle -->
                    <button class="hidden lg:block p-2 rounded hover:bg-slate-100" @click="collapsed = !collapsed">
                        ☰
                    </button>

                    <h1 class="font-semibold">Dashboard</h1>
                </div>

                <!-- User Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2">
                        <div class="text-right">
                            <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-500">{{ Auth::user()->role->name }}</div>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" @click.outside="open=false" x-transition
                        class="absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-slate-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>

</html>
