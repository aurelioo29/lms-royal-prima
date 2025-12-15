@php
    $user = auth()->user();

    $canPlanCreate = (bool) $user->role?->can_create_plans;
    $canPlanApprove = (bool) $user->role?->can_approve_plans;

    // menu parent muncul kalau user punya salah satu permission (atau developer, tapi itu udah otomatis via middleware)
    $showPlansMenu = $canPlanCreate || $canPlanApprove;

    $plansActive = request()->routeIs('annual-plans.*');
@endphp

<nav class="flex-1 p-2 space-y-1">

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
        {{-- Dashboard Icon --}}
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor"
                d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm13 0c-.55 0-1 .45-1 1v2h-2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-2v-2c0-.55-.45-1-1-1z" />
        </svg>
        <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">Dashboard</span>
    </a>

    {{-- Annual Plans --}}
    @if ($showPlansMenu)
        <div x-data="{ open: {{ $plansActive ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
            {{ $plansActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    {{-- icon kalender --}}
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm12 8H5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9ZM6 6H5a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1h-1v1a1 1 0 1 1-2 0V6H8v1a1 1 0 1 1-2 0V6Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Annual Plans
                    </span>
                </div>

                {{-- arrow --}}
                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                {{-- semua yang punya akses menu ini boleh lihat list --}}
                @if ($canPlanCreate)
                    <a href="{{ route('annual-plans.index') }}"
                        class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('annual-plans.index') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                        Daftar Plans
                    </a>

                    <a href="{{ route('annual-plans.create') }}"
                        class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('annual-plans.create') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                        Create New Plan
                    </a>
                @endif

                @if ($canPlanApprove)
                    <a href="{{ route('annual-plans.approvals') }}"
                        class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('annual-plans.approvals') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                        Manage Approvals
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- MANAGE USERS -->
    @if (auth()->user()->canManageUsers())
        <div x-data="{ open: false }" class="pt-1">

            <!-- Parent button -->
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">

                <div class="flex items-center gap-3">
                    {{-- Manage Users Icon (SVG dari kamu) --}}
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M14 19.5c0-2 1.1-3.8 2.7-4.7c-1.3-.5-2.9-.8-4.7-.8c-4.4 0-8 1.8-8 4v2h10v-.5m5.5-3.5c-1.9 0-3.5 1.6-3.5 3.5s1.6 3.5 3.5 3.5s3.5-1.6 3.5-3.5s-1.6-3.5-3.5-3.5M16 8c0 2.2-1.8 4-4 4s-4-1.8-4-4s1.8-4 4-4s4 1.8 4 4Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Manage Users
                    </span>
                </div>

                <!-- Dropdown arrow (gepeng SVG dari kamu) -->
                <svg x-show="!collapsed" x-transition.opacity
                    class="w-4 h-4 text-slate-500 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <!-- Submenu (animated) -->
            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <a href="{{ route('admin.mot.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                    Narasumber
                </a>

                <a href="{{ route('employees.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                    Karyawan
                </a>

                <a href="{{ route('job-titles.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                    Job Title
                </a>

                <a href="{{ route('job-categories.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                    Job Categories
                </a>

                <a href="{{ route('roles.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition">
                    Roles
                </a>
            </div>

        </div>
    @endif

</nav>

<!-- LOGOUT -->
<div class="p-2 border-t">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
                <path d="M7 8v8" />
            </svg>
            <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">Logout</span>
        </button>
    </form>
</div>
