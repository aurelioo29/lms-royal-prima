<header class="h-16 bg-white border-b flex items-center justify-between px-4 relative z-50">
    <div class="flex items-center gap-3">
        {{-- Toggle Button --}}
        <button @click="collapsed = !collapsed"
            class="p-2 rounded-md hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="font-semibold text-slate-700">Dashboard</h1>
    </div>

    {{-- Right: Dropdown --}}
    <div class="flex items-center gap-3">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="group inline-flex items-center gap-3 rounded-lg px-2 py-1.5 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200 transition">

                    <div class="text-right leading-tight hidden sm:block">
                        <div class="text-sm font-medium text-slate-700">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ Auth::user()->role->name ?? '' }}
                        </div>
                    </div>

                    <div
                        class="h-9 w-9 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
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
                    <div class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="border-t border-slate-100"></div>

                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
