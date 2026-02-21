<header
    class="h-16 bg-white/80 backdrop-blur
           border-b border-slate-300/90
           flex items-center justify-between px-4
           shadow-[0_1px_0_rgba(15,23,42,0.04)]
           relative isolate">

    <div class="flex items-center gap-3">
        <button type="button" @click="(window.innerWidth < 1024) ? (sidebarOpen = true) : (collapsed = !collapsed)"
            class="p-2 rounded-lg hover:bg-white/60 transition
           focus:outline-none focus:ring-2 focus:ring-[#121293]/20"
            aria-label="Toggle Sidebar">
            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="leading-tight">
            <h1 class="text-sm font-semibold text-slate-900">Dashboard</h1>
            <div class="text-[11px] text-slate-500 hidden sm:block">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <x-dropdown align="right" width="56" contentClasses="py-1 bg-white/95 backdrop-blur">
            <x-slot name="trigger">
                <button type="button"
                    class="group inline-flex items-center gap-3 rounded-xl px-2 py-1.5
                           hover:bg-white/60 transition
                           focus:outline-none focus:ring-2 focus:ring-[#121293]/20">

                    <div class="text-right leading-tight hidden sm:block">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ Auth::user()->role->name ?? '' }}
                        </div>
                    </div>

                    <div
                        class="h-9 w-9 rounded-full bg-[#121293] text-white flex items-center justify-center font-semibold ring-2 ring-white/70">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-700 transition"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3">
                    <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="border-t border-slate-200/80"></div>

                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout(event)"
                        class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
