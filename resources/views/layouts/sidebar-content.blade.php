@php
    $user = auth()->user();

    // permissions dari role (konsisten)
    $canPlanCreate = (bool) $user->role?->can_create_plans;
    $canPlanApprove = (bool) $user->role?->can_approve_plans;

    $canCourseCreate = (bool) $user->role?->can_create_courses;
    $canCourseApprove = (bool) $user->role?->can_approve_courses; // optional

    $canManageUsers = (bool) $user->role?->can_manage_users;

    // menu visibility
    $showPlansMenu = $canPlanCreate || $canPlanApprove;
    $showTorMenu = $canPlanCreate || $canPlanApprove; // TOR: Kabid buat, Direktur acc

    // ✅ admin course only (Courses + Course Types)
    $showCoursesMenu = $canCourseCreate;
    $showCourseTypesMenu = $canCourseCreate;

    $showUsersMenu = $canManageUsers;

    // active flags
    $plansActive = request()->routeIs('annual-plans.*');

    // TOR: route kamu pakai tor-submissions.*
    $torActive = request()->routeIs('tor-submissions.*');

    // Courses
    $coursesActive = request()->routeIs('courses.*');

    // ✅ Course Types
    $courseTypesActive = request()->routeIs('course-types.*');

    $manageUsersActive =
        request()->routeIs('roles.*') ||
        request()->routeIs('employees.*') ||
        request()->routeIs('job-titles.*') ||
        request()->routeIs('job-categories.*') ||
        request()->routeIs('admin.mot.*');

    // dropdown open state
    $openPlans = $plansActive;
    $openTor = $torActive;

    // ✅ pisah open state biar masing-masing section waras
    $openCourses = $coursesActive;
    $openCourseTypes = $courseTypesActive;

    $openUsers = $manageUsersActive;
@endphp

<nav class="flex-1 p-2 space-y-1">

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor"
                d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm13 0c-.55 0-1 .45-1 1v2h-2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-2v-2c0-.55-.45-1-1-1z" />
        </svg>
        <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">Dashboard</span>
    </a>

    {{-- ================= ANNUAL PLANS ================= --}}
    @if ($showPlansMenu)
        <div x-data="{ open: {{ $openPlans ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $plansActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm12 8H5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9ZM6 6H5a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1h-1v1a1 1 0 1 1-2 0V6H8v1a1 1 0 1 1-2 0V6Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Annual Plans
                    </span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <a href="{{ route('annual-plans.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('annual-plans.index') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Daftar Plans
                </a>

                @if ($canPlanCreate)
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

    {{-- ================= TOR ================= --}}
    @if ($showTorMenu)
        <div x-data="{ open: {{ $openTor ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
            {{ $torActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 1.5V8h4.5L14 3.5ZM7 12h10v2H7v-2Zm0 4h10v2H7v-2Zm0-8h6v2H7V8Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        TOR
                    </span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <div class="px-3 py-2 text-xs text-slate-500">
                    TOR dibuat dari detail Event (Annual Plan → Event → Buat TOR).
                </div>
            </div>
        </div>
    @endif

    {{-- ================= COURSES (Admin) ================= --}}
    @if ($showCoursesMenu)
        <div x-data="{ open: {{ $openCourses ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $coursesActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M6 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a3 3 0 0 1-3-3V4a2 2 0 0 1 2-2Zm0 2v15a1 1 0 0 0 1 1h9V4H6Zm2 3h6v2H8V7Zm0 4h6v2H8v-2Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Courses
                    </span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <a href="{{ route('courses.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('courses.index') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Daftar Courses
                </a>

                <a href="{{ route('courses.create') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('courses.create') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Create Course
                </a>
            </div>
        </div>
    @endif

    {{-- ================= COURSE TYPES (Admin) ================= --}}
    @if ($showCourseTypesMenu)
        <div x-data="{ open: {{ $openCourseTypes ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $courseTypesActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor" d="M4 4h16v4H4V4Zm0 6h16v4H4v-4Zm0 6h16v4H4v-4Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Course Types
                    </span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity class="w-4 h-4 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <a href="{{ route('course-types.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('course-types.index') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Daftar Types
                </a>

                <a href="{{ route('course-types.create') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('course-types.create') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Create Type
                </a>
            </div>
        </div>
    @endif

    {{-- ================= MANAGE USERS ================= --}}
    @if ($showUsersMenu)
        <div x-data="{ open: {{ $openUsers ? 'true' : 'false' }} }" class="pt-1">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                {{ $manageUsersActive ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M14 19.5c0-2 1.1-3.8 2.7-4.7c-1.3-.5-2.9-.8-4.7-.8c-4.4 0-8 1.8-8 4v2h10v-.5m5.5-3.5c-1.9 0-3.5 1.6-3.5 3.5s1.6 3.5 3.5 3.5s3.5-1.6 3.5-3.5s-1.6-3.5-3.5-3.5M16 8c0 2.2-1.8 4-4 4s-4-1.8-4-4s1.8-4 4-4s4 1.8 4 4Z" />
                    </svg>

                    <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">
                        Manage Users
                    </span>
                </div>

                <svg x-show="!collapsed" x-transition.opacity
                    class="w-4 h-4 text-slate-500 transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                </svg>
            </button>

            <div x-show="open && !collapsed" x-collapse.duration.250ms class="mt-1 space-y-1 pl-11">
                <a href="{{ route('admin.mot.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.mot.*') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Narasumber
                </a>

                <a href="{{ route('employees.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('employees.*') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Karyawan
                </a>

                <a href="{{ route('job-titles.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('job-titles.*') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Job Title
                </a>

                <a href="{{ route('job-categories.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('job-categories.*') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
                    Job Categories
                </a>

                <a href="{{ route('roles.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('roles.*') ? 'bg-slate-100 text-[#121293]' : 'text-slate-600 hover:bg-slate-100' }}">
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
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
                <path d="M7 8v8" />
            </svg>
            <span x-show="!collapsed" x-transition.opacity class="text-sm font-medium">Logout</span>
        </button>
    </form>
</div>
