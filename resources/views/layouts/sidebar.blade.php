<aside :class="collapsed ? 'w-16' : 'w-64'"
    class="bg-white border-r border-slate-200 transition-all duration-300 ease-in-out flex flex-col overflow-hidden">
    {{-- Brand / Logo (HANYA saat expanded) --}}
    <div x-show="!collapsed" x-transition.opacity.duration.200ms class="h-16 flex items-center justify-center border-b">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <img src="{{ asset('images/logo-royal.png') }}" class="h-10 w-auto select-none" alt="RSU Royal Prima"
                draggable="false">
        </a>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 p-2 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
            {{-- Pakai SVG dashboard yang kamu kasih (biar konsisten) --}}
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor"
                    d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm13 0c-.55 0-1 .45-1 1v2h-2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-2v-2c0-.55-.45-1-1-1z" />
            </svg>

            <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                Dashboard
            </span>
        </a>

        @if (auth()->user()->canManageUsers())
            <div x-data="{ open: false }" class="pt-1">
                {{-- Parent button --}}
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
                    <div class="flex items-center gap-3">
                        {{-- icon --}}
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a4 4 0 0 0-4-4h-1" />
                            <path d="M9 20H4v-2a4 4 0 0 1 4-4h1" />
                            <circle cx="9" cy="7" r="4" />
                            <circle cx="17" cy="7" r="4" />
                        </svg>

                        <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                            Manage Users
                        </span>
                    </div>

                    {{-- arrow (hanya saat expanded) --}}
                    <svg x-show="!collapsed" x-transition.opacity
                        class="w-4 h-4 text-slate-500 transition-transform duration-300"
                        :class="open ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                {{-- Submenu (animasi) --}}
                <div x-show="open && !collapsed" x-collapse.duration.250ms x-transition.opacity.duration.200ms
                    class="mt-1 space-y-1 pl-11">
                    <a href=""
                        class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                        Narasumber
                    </a>

                    <a href=""
                        class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                        Karyawan
                    </a>

                    <a href=""
                        class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                        Job Title
                    </a>

                    <a href=""
                        class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                        Job Categories
                    </a>

                    <a href=""
                        class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                        Roles
                    </a>
                </div>
            </div>
        @endif

    </nav>

    {{-- Logout --}}
    <div class="p-2 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
                    <path d="M7 8v8" />
                </svg>

                <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>
